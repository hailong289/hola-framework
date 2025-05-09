<?php
ini_set('error_reporting', E_STRICT);
/**
* set __DIR__ROOT
**/

define('__DIR__ROOT', __DIR__);

/**
 * Initialize the load registration function
 **/

$appRegister = new \Hola\Container\RegisterLoad();

/**
 *  Load the env
 **/

$appRegister->loadEnvironment();

/**
 *  Load the configs
 **/
$appRegister->loadConfig();

/**
 *  Load folders that you create in the project
 **/
// $appRegister->registerFolder([]);

/**
 *  Register session
 *  If you want to use session then open this code
 */

//  $appRegister->registerSession();

/**
 *  Load the router
 **/
$appRegister->routerWorkLoad();

/**
 *  Load timezone
 **/
$appRegister->loadTimeZone();

/**
 *  Load language
 *  If you want to use language then open this code
 *  default language is vi with variable LANGUAGE in config/constant.php
 *  If you want to change language then change value of LANGUAGE in config/constant.php
 *  Example: define('LANGUAGE', 'en');
 *  Then create file en.php in language folder
 **/
$appRegister->loadLanguage();

/**
 *  Initialize app
 **/
$appRegister->initApp();
