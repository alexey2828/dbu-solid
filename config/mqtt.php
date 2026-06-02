<?php

return [
    'url' => env('MQTT_URL'),
    'host' => env('MQTT_HOST', 'localhost'),
    'port' => env('MQTT_PORT', 1883),
    'username' => env('MQTT_USERNAME'),
    'password' => env('MQTT_PASSWORD'),
    'client_id_prefix' => env('MQTT_CLIENT_ID_PREFIX', 'laravel_mqtt_'),
    'topic_prefix' => env('MQTT_TOPIC_PREFIX', 'grand_beton/ttn/'),
    'quality_of_service' => (int) env('MQTT_QOS', 0),
    'retain' => filter_var(env('MQTT_RETAIN', false), FILTER_VALIDATE_BOOLEAN),
    'protocol' => env('MQTT_PROTOCOL', '3.1.1'),
    'connect_timeout' => (int) env('MQTT_CONNECT_TIMEOUT', 10),
    'use_tls' => filter_var(env('MQTT_USE_TLS', false), FILTER_VALIDATE_BOOLEAN),
    'tls_verify_peer' => filter_var(env('MQTT_TLS_VERIFY_PEER', true), FILTER_VALIDATE_BOOLEAN),
    'tls_verify_peer_name' => filter_var(env('MQTT_TLS_VERIFY_PEER_NAME', true), FILTER_VALIDATE_BOOLEAN),
    'tls_self_signed_allowed' => filter_var(env('MQTT_TLS_SELF_SIGNED_ALLOWED', false), FILTER_VALIDATE_BOOLEAN),
];
