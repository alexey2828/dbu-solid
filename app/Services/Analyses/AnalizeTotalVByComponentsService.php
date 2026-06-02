<?php

namespace App\Services\Analyses;

use App\Contracts\Services\Analyses\AnalizeTotalVByComponentsServiceInterface;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;

class AnalizeTotalVByComponentsService implements AnalizeTotalVByComponentsServiceInterface
{
    protected function createDateRange(string $start_date, string $end_date, string $step): array
    {
        $dates = [];
        $current = new DateTime($start_date);
        $end = new DateTime($end_date);

        switch ($step) {
            case 'hour':
                $current->setTime((int)$current->format('H'), 0, 0);
                $end->setTime((int)$end->format('H'), 0, 0);
                if ((int)$end->format('i') > 0 || (int)$end->format('s') > 0) {
                    $end->add(new DateInterval('PT1H'));
                }
                $format = 'Y-m-d H:00:00';
                $interval = new DateInterval('PT1H');
                break;
            case 'week':
                $current->setTime(0, 0, 0);
                $current->modify('monday this week');
                $end->setTime(0, 0, 0);
                $end->modify('monday this week');
                $end->add(new DateInterval('P1W'));
                $format = 'Y-m-d';
                $interval = new DateInterval('P1W');
                break;
            case 'month':
                $current->setDate((int)$current->format('Y'), (int)$current->format('m'), 1);
                $current->setTime(0, 0, 0);
                $end->setDate((int)$end->format('Y'), (int)$end->format('m'), 1);
                $end->setTime(0, 0, 0);
                $end->add(new DateInterval('P1M'));
                $format = 'Y-m-d';
                $interval = new DateInterval('P1M');
                break;
            case 'day':
            default:
                $current->setTime(0, 0, 0);
                $end->setTime(0, 0, 0);
                $end->add(new DateInterval('P1D'));
                $format = 'Y-m-d';
                $interval = new DateInterval('P1D');
        }

        while ($current < $end) {
            $dates[] = $current->format($format);
            $current->add($interval);
        }

        return $dates;
    }

    protected function getGroupByExpression(string $step): string
    {
        switch ($step) {
            case 'hour':
                return "DATE_FORMAT(p.timeEnd, '%Y-%m-%d %H:00:00')";
            case 'day':
                return "DATE(p.timeEnd)";
            case 'week':
                return "DATE_FORMAT(p.timeEnd, '%Y-%u')";
            case 'month':
                return "DATE_FORMAT(p.timeEnd, '%Y-%m-01')";
            default:
                return "DATE(p.timeEnd)";
        }
    }

    /**
     * @inheritDoc
     */
    public function analyze(array $params): array
    {
        $timeStart = $params['timeStart'] ?? null;
        $timeEnd = $params['timeEnd'] ?? null;
        $step = $params['step'] ?? 'day';
        $compCode = $params['compCode'] ?? null;
        $bsuCode = $params['bsuCode'] ?? null;
        $car = $params['car'] ?? null;
        $driver = $params['driver'] ?? null;
        $orderId = $params['order'] ?? null;
        $dispencer = $params['dispencer'] ?? null;
        $idTtn = $params['idTtn'] ?? null;
        $codePlant = $params['codePlant'] ?? null;

        $bsuCodes = [];
        if ($dispencer) {
            $bsuCodes = DB::table('silcem')->where('code', $dispencer)->pluck('codeBSU')->map(fn($v) => (int)$v)->toArray();
        }

        if ($codePlant) {
            $plantBsuCodes = DB::table('bsu')->where('codePlant', $codePlant)->pluck('code')->map(fn($v) => (int)$v)->toArray();
            $bsuCodes = array_merge($bsuCodes, $plantBsuCodes);
        }

        if ($bsuCode) {
            $bsuCodes[] = (int)$bsuCode;
        }

        $bsuCodes = array_unique($bsuCodes);

        $ttnIds = [];
        if ($orderId) {
            $ttnIds = DB::table('ttn')->where('idOrder', $orderId)->pluck('id')->map(fn($v) => (int)$v)->toArray();
        }

        if ($timeStart && $timeEnd) {
            if ($step === 'hour') {
                $startDateTime = date('Y-m-d H:00:00', strtotime($timeStart));
                $endDateTime = date('Y-m-d H:59:59', strtotime($timeEnd));
            } else {
                $startDateTime = date('Y-m-d 00:00:00', strtotime($timeStart));
                $endDateTime = date('Y-m-d 23:59:59', strtotime($timeEnd));
            }
        } else {
            $startDateTime = null;
            $endDateTime = null;
        }

        $groupByExpression = $this->getGroupByExpression($step);

        $query = DB::table('product as p')
            ->selectRaw("{$groupByExpression} as group_date, SUM(rcl.weightFactLoop) as fact_sum")
            ->join('reportCurrentLoop as rcl', 'p.id', '=', 'rcl.idProduct')
            ->where('rcl.code', $compCode ?: 0);

        if ($startDateTime && $endDateTime) {
            $query->whereBetween('p.timeEnd', [$startDateTime, $endDateTime]);
        }

        if (!empty($bsuCodes)) {
            $query->whereIn('p.idPlant', $bsuCodes);
        }

        if (!empty($ttnIds)) {
            $query->whereIn('p.idTtn', $ttnIds);
        }

        if ($idTtn) {
            $query->where('p.idTtn', $idTtn);
        }

        if ($car) {
            $query->where('p.car', $car);
        }

        if ($driver) {
            $query->where('p.driver', $driver);
        }

        if ($dispencer) {
            $query->where('rcl.dispencer', $dispencer);
        }

        $query->groupBy('group_date')->orderBy('group_date');

        $rows = $query->get();

        $progressByDate = [];
        foreach ($rows as $row) {
            $groupDate = $row->group_date;
            if ($step === 'week') {
                [$year, $week] = explode('-', $groupDate);
                $date = new DateTime();
                $date->setISODate((int)$year, (int)$week);
                $groupDate = $date->format('Y-m-d');
            }
            $progressByDate[$groupDate] = (float)$row->fact_sum;
        }

        $dateRange = [];
        $consumptionValues = [];
        if ($timeStart && $timeEnd) {
            $dateRange = $this->createDateRange($timeStart, $timeEnd, $step);
            foreach ($dateRange as $date) {
                $consumptionValues[] = round($progressByDate[$date] ?? 0, 2);
            }
        } else {
            foreach ($progressByDate as $date => $value) {
                $dateRange[] = $date;
                $consumptionValues[] = round($value, 2);
            }
        }

        return [
            'dateRange' => $dateRange,
            'cumulativeValues' => $consumptionValues,
            'step' => $step,
        ];
    }
}
