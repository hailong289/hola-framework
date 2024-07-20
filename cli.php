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
$app->add(new \Scripts\ControllerScript());
$app->add(new \Scripts\ModelScript());
$app->add(new \Scripts\ViewScript());
$app->add(new \Scripts\RequestScript());
$app->add(new \Scripts\MiddlewareScript());
$app->add(new \Scripts\QueueScript());
$app->add(new \Scripts\CommandScript());
$app->add(new \Scripts\MailScript());
$app->add(new \Scripts\RouterScript());
$app->add(new \Scripts\CacheScript());
$app->run();


