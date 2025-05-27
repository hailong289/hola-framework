<?php

return [
    /**
     * connection
     */
    "default" => conval('QUEUE_WORK', "database"),
    "default_connections" => conval('QUEUE_CONNECTION', "database"),
    /**
     * queue key
     */
    "queue_default" => "jobs",

    /**
     * Queue runtime
     * currently defaults to 10 minutes
     */
    "timeout" => conval('QUEUE_TIMEOUT', 600),

    "connections" => [
        "database" => [
            'driver' => 'mysql',
            "host" => conval('DB_HOST', '127.0.0.1'),
            "port" => conval('DB_PORT', '3306'),
            "db_name" => conval('DB_NAME', 'blog'),
            "username" => conval('DB_USERNAME', 'root'),
            "password" => conval('DB_PASSWORD', ''),
            "options" => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ],
        ],
        "redis" => [
            'driver' => 'redis',
            'host' => conval('REDIS_HOST', '127.0.0.1'),
            'port' => conval('REDIS_PORT', '6379'),
            'username' => conval('REDIS_USER', 'default'),
            'password' => conval('REDIS_PASSWORD', null),
            'timeout' => 0,
            'reserved' => null,
            'retryInterval' => 0,
            'readTimeout' => 0.0
        ],
        "rabbitmq" => [
            'driver' => 'rabbitmq',
            "host" => conval('RABBITMQ_HOST', '127.0.0.1'),
            "port" => conval('RABBITMQ_PORT', '5672'),
            "username" => conval('RABBITMQ_USER', 'default'),
            "password" => conval('RABBITMQ_PASSWORD', ''),
            "vhost" => conval('RABBITMQ_VHOST', '/'),
            "scheme" => conval('RABBITMQ_SCHEME', ''),
            "options" => [
                'cafile' => null,
                'local_cert' =>null,
                'local_key' => null,
                'verify_peer' => false,
                'passphrase' => null,
            ],
        ]
    ]
];