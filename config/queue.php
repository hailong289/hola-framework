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
            "host" => config_env('DB_HOST', '127.0.0.1'),
            "port" => config_env('DB_PORT', '3306'),
            "db_name" => config_env('DB_NAME', 'blog'),
            "username" => config_env('DB_USERNAME', 'root'),
            "password" => config_env('DB_PASSWORD', ''),
            "options" => config_env('DB_OPTIONS', [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]),
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