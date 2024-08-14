<?php
require 'vendor/autoload.php';
define('__DIR__ROOT', __DIR__);
// load config
$appRegister = new \Hola\Core\RegisterLoad();
$appRegister->registerFolder('config');
$appRegister->loadTimeZone();
 // register command
$application = new \Hola\Application();
$application->registerCommand();


