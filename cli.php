<?php
require 'vendor/autoload.php';
define('__DIR__ROOT', __DIR__);
ini_set('error_reporting', E_STRICT);
// load config
$appRegister = new \Hola\Container\RegisterLoad();
$appRegister->initCLI();
 // register command
$application = new App\App();
$application->registerCommand();
$application->runCLI();
