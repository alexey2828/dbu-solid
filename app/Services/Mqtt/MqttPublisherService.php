<?php

namespace App\Services\Mqtt;

use App\Contracts\Services\Mqtt\MqttPublisherServiceInterface;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;

class MqttPublisherService implements MqttPublisherServiceInterface
{
    public function publish(string $topic, string $message, int $qualityOfService = 0, bool $retain = false): bool
    {
        $config = config('mqtt');
        $broker = $this->resolveBrokerConfig($config);
        $protocol = $config['protocol'] === '3.1.1'
            ? MqttClient::MQTT_3_1_1
            : MqttClient::MQTT_3_1;

        $clientId = $config['client_id_prefix'] . uniqid('', true);

        $connectionSettings = (new ConnectionSettings())
            ->setUsername($broker['username'])
            ->setPassword($broker['password'])
            ->setConnectTimeout((int) $config['connect_timeout'])
            ->setUseTls($broker['useTls'])
            ->setTlsVerifyPeer($config['tls_verify_peer'])
            ->setTlsVerifyPeerName($config['tls_verify_peer_name'])
            ->setTlsSelfSignedAllowed($config['tls_self_signed_allowed']);

        $client = new MqttClient(
            $broker['host'],
            $broker['port'],
            $clientId,
            $protocol
        );

        try {
            $client->connect($connectionSettings, true);
            $client->publish($topic, $message, $qualityOfService, $retain);
            $client->disconnect();

            return true;
        } catch (MqttClientException $e) {
            logger()->error('MQTT publish failed', [
                'topic' => $topic,
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException('MQTT publish failed for topic ' . $topic . ': ' . $e->getMessage(), 0, $e);
        }
    }

    private function resolveBrokerConfig(array $config): array
    {
        $host = $config['host'];
        $port = (int) $config['port'];
        $username = $config['username'];
        $password = $config['password'];
        $useTls = $config['use_tls'];

        if (! empty($config['url'])) {
            $url = $config['url'];

            if (! str_contains($url, '://')) {
                $url = 'mqtt://' . $url;
            }

            $parsed = parse_url($url);

            if (! $parsed || empty($parsed['host'])) {
                throw new \InvalidArgumentException('Invalid MQTT URL configured.');
            }

            $host = $parsed['host'];

            if (! empty($parsed['port'])) {
                $port = (int) $parsed['port'];
            }

            if (! empty($parsed['user']) && $username === null) {
                $username = rawurldecode($parsed['user']);
            }

            if (! empty($parsed['pass']) && $password === null) {
                $password = rawurldecode($parsed['pass']);
            }

            $scheme = strtolower($parsed['scheme'] ?? '');
            $useTls = $useTls || in_array($scheme, ['mqtts', 'ssl'], true);
        }

        return [
            'host' => $host,
            'port' => $port,
            'username' => $username,
            'password' => $password,
            'useTls' => (bool) $useTls,
        ];
    }
}
