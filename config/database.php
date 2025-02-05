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
//    "mysql_production" => [
//        "host" => conval('DB_HOST_PRODUCTION', '127.0.0.1'),
//        "port" => conval('DB_PORT_PRODUCTION', '3306'),
//        "db_name" => conval('DB_NAME_PRODUCTION', 'default'),
//        "username" => conval('DB_USERNAME_PRODUCTION', 'root'),
//        "password" => conval('DB_PASSWORD_PRODUCTION', ''),
//        "options" => conval('DB_OPTIONS', [
//            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//        ]),
//    ],
        "pgsql" => [
            'driver' => 'pgsql',
            "host" => conval('DB_HOST_pgsql', '127.0.0.1'),
            "port" => conval('DB_PORT_pgsql', '5432'),
            "db_name" => conval('DB_NAME_pgsql', 'default'),
            "username" => conval('DB_USERNAME_pgsql', 'postgres'),
            "password" => conval('DB_PASSWORD_pgsql', 'postgres'),
            "options" => conval('DB_OPTIONS_pgsql',null),
        ],
//    "pgsql_production" => [
//        "host" => conval('DB_HOST_PRODUCTION', 'postgres_host'),
//        "port" => conval('DB_PORT_PRODUCTION', '5432'),
//        "db_name" => conval('DB_NAME_PRODUCTION', 'default'),
//        "username" => conval('DB_USERNAME_PRODUCTION', 'postgres'),
//        "password" => conval('DB_PASSWORD_PRODUCTION', 'postgres'),
//        "options" => conval('DB_OPTIONS', [
//            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//        ]),
//    ],
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
//    "redis_production" => [
//        'host' => conval('REDIS_HOST_PRODUCTION', '127.0.0.1'),
//        'port' => conval('REDIS_PORT_PRODUCTION', '6379'),
//        'username' => conval('REDIS_USER_PRODUCTION', 'default'),
//        'password' => conval('REDIS_PASSWORD_PRODUCTION', null),
//        'timeout' => 0,
//        'reserved' => null,
//        'retryInterval' => 0,
//        'readTimeout' => 0.0
//    ]
    ]
];


