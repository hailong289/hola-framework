<?php
ini_set('error_reporting', E_STRICT);
define('__DIR__ROOT', __DIR__);

$appRegister = new \System\Core\RegisterLoad();

$appRegister->registerFolder([
    'config'
]);

$appRegister
    ->languageLoad()
    ->routerWorkLoad()
    ->initApp();
