<?php
const URL_PATH = 'http://localhost';
const DEBUG_LOG = true;
const DEBUG_LOG_CONNECTION = false;
const LANGUAGE = 'vi';
const TIMEZONE = 'Asia/Ho_Chi_Minh';

// connection db
const DB_ENVIRONMENT = 'default';
const DB_CONNECTION = 'mysql';
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'blog';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_OPTIONS = [
    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
];
// redis
const REDIS_ENVIRONMENT = 'default';
const REDIS_CONNECTION = 'redis';
const REDIS_HOST = '127.0.0.1';
const REDIS_PORT = '6379';
const REDIS_USER = 'default';
const REDIS_PASSWORD = null;
// end db

// Rabbit MQ
const RABBITMQ_HOST = '127.0.0.1';
const RABBITMQ_VHOST = '/';
const RABBITMQ_PORT = '5671';
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

// Queue
const QUEUE_WORK = 'database'; // use database or redis or rabbitMQ
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