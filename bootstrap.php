<?php
ini_set('error_reporting', E_STRICT);
define('__DIR__ROOT', __DIR__);

// auto load file
$config_dir = glob('config/*.php');
if (!empty($config_dir)) {
    foreach($config_dir as $item){
        if(file_exists(path_root($item))){
            require_once path_root($item);
        }
    }
}

$language = glob('language/*.php');
if (!empty($language)) {
    foreach($language as $item){
        if(file_exists(path_root($item))){
            if($item === "language/".LANGUAGE.".php") {
                $GLOBALS['data_lang'] = require(path_root($item));
            }
        }
    }
}

require_once 'router/index.php';
require_once 'App/App.php';
