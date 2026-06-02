<?php

namespace App\Contracts\Services\Mqtt;

interface MqttPublisherServiceInterface
{
    public function publish(string $topic, string $message, int $qualityOfService = 0, bool $retain = false): bool;
}
