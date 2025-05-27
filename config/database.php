<?php

return [
    /**
     * connection
     */
    "default" => "mysql",
    /**
     * connection list
     */
    "connections" => [
        "mysql" => [
            'driver' => 'mysql',
            "host" => conval('DB_HOST', '127.0.0.1'),
            "port" => conval('DB_PORT', '3306'),
            "db_name" => conval('DB_NAME', 'blog'),
            "username" => conval('DB_USERNAME', 'root'),
            "password" => conval('DB_PASSWORD', ''),
            "options" => conval('DB_OPTIONS', null),
        ],
        "pgsql" => [
            'driver' => 'pgsql',
            "host" => conval('DB_HOST_PGSQL', '127.0.0.1'),
            "port" => conval('DB_PORT_PGSQL', '5432'),
            "db_name" => conval('DB_NAME_PGSQL', 'default'),
            "username" => conval('DB_USERNAME_PGSQL', 'postgres'),
            "password" => conval('DB_PASSWORD_PGSQL', 'postgres'),
            "options" => conval('DB_OPTIONS_PGSQL',null),
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
        ]
    ]
];


