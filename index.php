<?php
/**
 * load test
 * open this code if you want to test
 */
//require_once "test.php";

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