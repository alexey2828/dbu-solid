<?php

use App\Contracts\Repositories\TtnRepositoryInterface;
use App\Contracts\Services\Mqtt\MqttPublisherServiceInterface;
use App\Services\Ttn\TtnUpdateService;
use App\Models\Ttn;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->repository = Mockery::mock(TtnRepositoryInterface::class);
    $this->mqttPublisher = Mockery::mock(MqttPublisherServiceInterface::class);
    $this->service = new TtnUpdateService($this->repository, $this->mqttPublisher);
});

afterEach(function () {
    Mockery::close();
});

test('it throws when mqtt publish fails after update', function () {
    $ttn = new Ttn(['idOrder' => 237]);
    $ttn->id = 757;

    $data = [
        'idOrder' => 237,
        'idPlant' => 1,
        'dispatcher' => 'Иванов',
        'driver' => 'Петров',
        'car' => 'A123BC',
        'finishAdress' => 'ул. Ленина, 10',
        'finishDate' => '2026-06-02 12:00:00',
        'bsu' => 256,
        'json' => ['cargo' => 'бетон'],
    ];

    $this->repository
        ->shouldReceive('update')
        ->once()
        ->with(757, Arr::only($data, ['idPlant', 'dispatcher', 'driver', 'car', 'finishAdress', 'finishDate']))
        ->andReturn($ttn);

    $this->mqttPublisher
        ->shouldReceive('publish')
        ->once()
        ->andThrow(new RuntimeException('MQTT publish failed for topic grand_beton/ttn/256: connection refused'));

    $this->service->updateAndPublish($ttn, $data);
})->throws(RuntimeException::class);
