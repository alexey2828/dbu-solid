<?php

use App\Services\MqttPublisherService;
use Tests\TestCase;

uses(TestCase::class);

it('parses mqtt url and applies tls when use_tls is enabled', function () {
    config()->set('mqtt.url', 'mqtt://asaiot.net:16760');
    config()->set('mqtt.host', 'localhost');
    config()->set('mqtt.port', 1883);
    config()->set('mqtt.username', 'fomin_a');
    config()->set('mqtt.password', 'Hs6#2vG#%8bxsKZf4');
    config()->set('mqtt.use_tls', true);

    $service = new MqttPublisherService();
    $method = new ReflectionMethod($service, 'resolveBrokerConfig');
    $method->setAccessible(true);

    $result = $method->invoke($service, config('mqtt'));

    expect($result)->toBe([
        'host' => 'asaiot.net',
        'port' => 16760,
        'username' => 'fomin_a',
        'password' => 'Hs6#2vG#%8bxsKZf4',
        'useTls' => true,
    ]);
});
