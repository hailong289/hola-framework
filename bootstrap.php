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
 *  or folders that you create in the project
 **/
$appRegister->registerFolder([
    'config'
]);

/**
 *  Register session
 *  If you want to use session then open this code
 */

//  $appRegister->registerSession();

/**
 *  Load the language
 *   If you do not want to default load using LANGUAGE constant in configs/constant.php
 *   then you can pass parameters to the languageLoad function below. For example:
 *   languageLoad('vi') or languageLoad('en')
 **/
$appRegister->languageLoad();

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
