<?php
ini_set('error_reporting', E_STRICT);
/**
* set __DIR__ROOT
**/

define('__DIR__ROOT', __DIR__);

/**
 * Initialize the load registration function
 **/

$appRegister = new \Hola\Core\RegisterLoad();

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
 *  Initialize app
 **/
$appRegister->initApp();
