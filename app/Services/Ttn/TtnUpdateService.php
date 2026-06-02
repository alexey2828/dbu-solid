<?php

namespace App\Services\Ttn;

use App\Contracts\Repositories\TtnRepositoryInterface;
use App\Contracts\Services\Mqtt\MqttPublisherServiceInterface;
use App\Contracts\Services\Ttn\TtnUpdateServiceInterface;
use App\Models\Ttn;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class TtnUpdateService implements TtnUpdateServiceInterface
{
    public function __construct(
        private TtnRepositoryInterface $repository,
        private MqttPublisherServiceInterface $mqttPublisher
    ) {}

    public function updateAndPublish(Ttn $ttn, array $data): Ttn
    {
        if ((int) ($data['idOrder'] ?? 0) !== (int) $ttn->idOrder) {
            throw new \InvalidArgumentException('The provided idOrder does not match the TTN record.');
        }

        $payload = Arr::only($data, [
            'idPlant',
            'dispatcher',
            'driver',
            'car',
            'finishAdress',
            'finishDate',
        ]);

        $record = $this->repository->update($ttn->id, $payload);

        $bsu = $data['bsu'] ?? $data['idBsu'] ?? null;

        if ($bsu === null) {
            throw new \InvalidArgumentException('BSU value is required for MQTT topic generation.');
        }

        $topic = config('mqtt.topic_prefix') . $bsu;
        $messageData = [
            'datetime' => Carbon::now('Europe/Kyiv')->format('Y-m-d H:i:s'),
            'st' => 8,
            'id' => $ttn->id,
            'ttn' => Arr::wrap($data['json'] ?? null),
        ];

        $published = $this->mqttPublisher->publish(
            $topic,
            json_encode($messageData),
            config('mqtt.quality_of_service', 0),
            config('mqtt.retain', false)
        );

        if (! $published) {
            throw new \RuntimeException('MQTT publish failed for topic ' . $topic);
        }

        return $record;
    }
}
