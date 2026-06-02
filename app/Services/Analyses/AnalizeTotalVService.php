<?php

namespace App\Services\Analyses;

use App\Contracts\Services\Analyses\AnalizeTotalVServiceInterface;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;

class AnalizeTotalVService implements AnalizeTotalVServiceInterface
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
        $bsuCode = $params['bsuCode'] ?? null;
        $car = $params['car'] ?? null;
        $driver = $params['driver'] ?? null;
        $orderId = $params['order'] ?? null;
        $dispencer = $params['dispencer'] ?? null;
        $idTtn = $params['idTtn'] ?? null;

        $bsuCodes = [];
        if ($dispencer) {
            $bsuCodes = DB::table('silcem')->where('code', $dispencer)->pluck('codeBSU')->map(fn($v) => (int)$v)->toArray();
        }

        if ($bsuCode) {
            $bsuCodes[] = (int)$bsuCode;
        }

        $bsuCodes = array_unique($bsuCodes);

        $ttnIds = [];
        if ($orderId) {
            $ttnIds = DB::table('ttn')->where('idOrder', $orderId)->pluck('id')->map(fn($v) => (int)$v)->toArray();
        }

        // Prepare date filters
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
            ->selectRaw("{$groupByExpression} as group_date, SUM(p.vProduct) as total_v_product");

        if ($dispencer) {
            $query->join('reportCurrentLoop as rcl', 'p.id', '=', 'rcl.idProduct')
                  ->where('rcl.dispencer', $dispencer);
        }

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

        $query->groupBy('group_date')->orderBy('group_date');

        $rows = $query->get();

        // Map results
        $groupedData = [];
        foreach ($rows as $row) {
            $groupKey = $row->group_date;
            $vProduct = (float)$row->total_v_product;

            if ($step === 'week') {
                [$year, $week] = explode('-', $groupKey);
                $date = new DateTime();
                $date->setISODate((int)$year, (int)$week);
                $groupKey = $date->format('Y-m-d');
            }

            $groupedData[$groupKey] = round($vProduct, 2);
        }

        $dateRange = [];
        $totalVProductArray = [];

        if ($timeStart && $timeEnd) {
            $dateRange = $this->createDateRange($timeStart, $timeEnd, $step);
            foreach ($dateRange as $date) {
                $totalVProductArray[] = $groupedData[$date] ?? 0;
            }
        } else {
            foreach ($groupedData as $date => $value) {
                $dateRange[] = $date;
                $totalVProductArray[] = $value;
            }
        }

        return [
            'dateRange' => $dateRange,
            'totalVProduct' => $totalVProductArray,
        ];
    }
}
