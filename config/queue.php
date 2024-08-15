<?php

return [
    /**
     * connection
     */
    "default_connection" => config_env('QUEUE_WORK', "database"),

    /**
     * queue key
     */
    "queue_default" => "jobs",

    /**
     * Queue runtime
     * currently defaults to 10 minutes
     */
    "timeout" => config_env('QUEUE_TIMEOUT', 600),

    "connections" => [
        "database" => [
            "HOST" => config_env('DB_HOST', '127.0.0.1'),
            "PORT" => config_env('DB_PORT', '3306'),
            "DATABASE_NAME" => config_env('DB_NAME', 'blog'),
            "USERNAME" => config_env('DB_USERNAME', 'root'),
            "PASSWORD" => config_env('DB_PASSWORD', '')
        ],
        "redis" => [
            'host' => config_env('REDIS_HOST', '127.0.0.1'),
            'port' => config_env('REDIS_PORT', '6379'),
            'username' => config_env('REDIS_USER', 'default'),
            'password' => config_env('REDIS_PASSWORD', null),
            'timeout' => 0,
            'reserved' => null,
            'retryInterval' => 0,
            'readTimeout' => 0.0
        ],
        "rabbitmq" => [
            "host" => config_env('RABBITMQ_HOST', '127.0.0.1'),
            "port" => config_env('RABBITMQ_PORT', '5672'),
            "username" => config_env('RABBITMQ_USER', 'default'),
            "password" => config_env('RABBITMQ_PASSWORD', ''),
            "vhost" => config_env('RABBITMQ_VHOST', '/'),
            "scheme" => config_env('RABBITMQ_SCHEME', ''),
            "options" => config_env('RABBITMQ_OPTIONS', [
                'cafile' => null,
                'local_cert' =>null,
                'local_key' => null,
                'verify_peer' => false,
                'passphrase' => null,
            ]),
        ]
    ]
];