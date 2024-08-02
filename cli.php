<?php
require 'vendor/autoload.php';
require_once 'config/constant.php';
require_once 'config/database.php';
define('__DIR__ROOT', __DIR__);
date_default_timezone_set(TIMEZONE);
$app = new \Symfony\Component\Console\Application();
$command_dir = glob('commands/*.php');
if (!empty($command_dir)) {
    foreach($command_dir as $item){
        $item = str_replace('.php','',$item);
        $class = str_replace('commands/','\Commands\\',$item);
        $app->add(new $class());
    }
}
$app->add(new \Hola\Scripts\ControllerScript());
$app->add(new \Hola\Scripts\ModelScript());
$app->add(new \Hola\Scripts\ViewScript());
$app->add(new \Hola\Scripts\RequestScript());
$app->add(new \Hola\Scripts\MiddlewareScript());
$app->add(new \Hola\Scripts\QueueScript());
$app->add(new \Hola\Scripts\CommandScript());
$app->add(new \Hola\Scripts\MailScript());
$app->add(new \Hola\Scripts\RouterScript());
$app->add(new \Hola\Scripts\CacheScript());
$app->run();


