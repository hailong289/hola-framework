<?php

return [
    /**
     * connection
     */
    "default_connection" => "mysql",
    /**
     * connection list
     */
    "connections" => [
        "mysql" => [
            'driver' => 'mysql',
            "host" => config_env('DB_HOST', '127.0.0.1'),
            "port" => config_env('DB_PORT', '3306'),
            "db_name" => config_env('DB_NAME', 'blog'),
            "username" => config_env('DB_USERNAME', 'root'),
            "password" => config_env('DB_PASSWORD', ''),
            "options" => config_env('DB_OPTIONS', null),
        ],
//    "mysql_production" => [
//        "host" => config_env('DB_HOST_PRODUCTION', '127.0.0.1'),
//        "port" => config_env('DB_PORT_PRODUCTION', '3306'),
//        "db_name" => config_env('DB_NAME_PRODUCTION', 'default'),
//        "username" => config_env('DB_USERNAME_PRODUCTION', 'root'),
//        "password" => config_env('DB_PASSWORD_PRODUCTION', ''),
//        "options" => config_env('DB_OPTIONS', [
//            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//        ]),
//    ],
        "pgsql" => [
            'driver' => 'pgsql',
            "host" => config_env('DB_HOST', 'postgres_host'),
            "port" => config_env('DB_PORT', '5432'),
            "db_name" => config_env('DB_NAME', 'default'),
            "username" => config_env('DB_USERNAME', 'postgres'),
            "password" => config_env('DB_PASSWORD', 'postgres'),
            "options" => config_env('DB_OPTIONS',null),
        ],
//    "pgsql_production" => [
//        "host" => config_env('DB_HOST_PRODUCTION', 'postgres_host'),
//        "port" => config_env('DB_PORT_PRODUCTION', '5432'),
//        "db_name" => config_env('DB_NAME_PRODUCTION', 'default'),
//        "username" => config_env('DB_USERNAME_PRODUCTION', 'postgres'),
//        "password" => config_env('DB_PASSWORD_PRODUCTION', 'postgres'),
//        "options" => config_env('DB_OPTIONS', [
//            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//        ]),
//    ],
        "redis" => [
            'driver' => 'redis',
            'host' => config_env('REDIS_HOST', '127.0.0.1'),
            'port' => config_env('REDIS_PORT', '6379'),
            'username' => config_env('REDIS_USER', 'default'),
            'password' => config_env('REDIS_PASSWORD', null),
            'timeout' => 0,
            'reserved' => null,
            'retryInterval' => 0,
            'readTimeout' => 0.0

        ],
//    "redis_production" => [
//        'host' => config_env('REDIS_HOST_PRODUCTION', '127.0.0.1'),
//        'port' => config_env('REDIS_PORT_PRODUCTION', '6379'),
//        'username' => config_env('REDIS_USER_PRODUCTION', 'default'),
//        'password' => config_env('REDIS_PASSWORD_PRODUCTION', null),
//        'timeout' => 0,
//        'reserved' => null,
//        'retryInterval' => 0,
//        'readTimeout' => 0.0
//    ]
    ]
];


