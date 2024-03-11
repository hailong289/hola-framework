<?php
define('URL_PATH', 'http://localhost');
define('CONNECT_REDIS', false); // coming soon
define('DEBUG_LOG', true);
define('LANGUAGE', 'vi');
define('TIMEZONE', 'Asia/Ho_Chi_Minh');

// connection db
define('DB_ENVIRONMENT', 'default'); // environment
define('DB_CONNECTION', 'mysql'); // chỉ hỗ trợ mysql, pgsql
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'blog');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
// redis
define('REDIS_CONNECTION', 'redis');
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', '6379');
define('REDIS_USER', 'default');
define('REDIS_PASSWORD', null);
// end db

// queue
define('QUEUE_WORK', 'redis'); // use database or redis

// mail
define('MAIL_CONNECTION', 'smtp');
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 465);
define('MAIL_USERNAME', 'username@gmail.com');
define('MAIL_PASSWORD', 'password');
define('MAIL_ENCRYPTION', 'ssl');
define('MAIL_DEBUG', 0);
//(0) No debug output, default
//(1) Client commands
//(2) Client commands and server responses
//(3) As DEBUG_SERVER plus connection status
//(4) Low-level data output, all messages.
// end mail