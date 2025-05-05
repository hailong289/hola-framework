<?php
const URL_PATH = 'http://localhost';
const APP_DEBUG = false; // 0: off, 1: on
const PROJECT_KEY='evAcrka08jQfbHznXbx5W47H6bZKJ6Jg';
const LANGUAGE = 'vi';
const TIMEZONE = 'Asia/Ho_Chi_Minh';

// connection db
const DB_CONNECTION = 'mysql';
const DB_HOST = 'mysql_container';
const DB_PORT = '3306';
const DB_NAME = 'blog';
const DB_USERNAME = 'root';
const DB_PASSWORD = '1';
const DB_OPTIONS = [
    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
];
// redis
const REDIS_HOST = '127.0.0.1';
const REDIS_PORT = '6379';
const REDIS_USER = 'default';
const REDIS_PASSWORD = null;
// end db

// Rabbit MQ
const RABBITMQ_CONNECTION = 'rabbitmq';
const RABBITMQ_HOST = '127.0.0.1';
const RABBITMQ_VHOST = '/';
const RABBITMQ_PORT = '5672';
const RABBITMQ_USER = 'guest';
const RABBITMQ_PASSWORD = 'guest';
const RABBITMQ_SCHEME = '';
const RABBITMQ_OPTIONS = [
    'cafile' => null,
    'local_cert' =>null,
    'local_key' => null,
    'verify_peer' => false,
    'passphrase' => null,
];
// end Rabbit MQ

// Cache
const CACHE_STORE = 'file'; // use file or redis

// Queue
const QUEUE_WORK = 'database'; // use database or redis or rabbitMQ
const QUEUE_CONNECTION = 'database'; // name connections in config/queue.php
const QUEUE_TIMEOUT = 600; // default timeout 10 minutes

// mail
const MAIL_CONNECTION = 'smtp';
const MAIL_HOST = 'smtp.gmail.com';
const MAIL_PORT = '465';
const MAIL_USERNAME = 'username@gmail.com';
const MAIL_PASSWORD = 'password';
const MAIL_ENCRYPTION = 'ssl';
const MAIL_DEBUG = 0;
//(0) No debug output, default
//(1) Client commands
//(2) Client commands and server responses
//(3) As DEBUG_SERVER plus connection status
//(4) Low-level data output, all messages.
// end mail