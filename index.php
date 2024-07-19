<?php
/**
 * load autoload
 */
require 'vendor/autoload.php';

/*
 *  load bootstrap
 */

require_once "bootstrap.php";

/*
* Initialize the app and run
*/

$app = new App\App();

$app->run();