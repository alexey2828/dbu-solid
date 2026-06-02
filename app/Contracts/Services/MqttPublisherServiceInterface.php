<?php

namespace App\Contracts\Services;

interface MqttPublisherServiceInterface
{
    public function publish(string $topic, string $message, int $qualityOfService = 0, bool $retain = false): bool;
}
