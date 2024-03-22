<?php
require_once 'config/constant.php';
define('__DIR__ROOT', __DIR__);
date_default_timezone_set(TIMEZONE);
$name_path_script =  'vendor/longdhdev/holaframework/scripts/';
if(count($argv)) unset($argv[0]);
$command = array_values($argv);
$module = explode(':', $command[0]);
$name_type_control = $module[0];
$name_control = $module[1];
if(!in_array($name_type_control,['create','run'])){
    echo "$name_type_control:$name_control does not exist";
    exit();
}
switch ($name_control) {
    case 'controller':
        unset($command[0]);
        $name_controller = array_values($command)[0] ?? '';
        if(empty($name_controller)) {
            echo 'The controller is not null';
            exit();
        }
        require_once $name_path_script.'controller.php';
        break;
    case 'middleware':
        unset($command[0]);
        $name_middleware = array_values($command)[0] ?? '';
        if(empty($name_middleware)) {
            echo 'The middleware is not null';
            exit();
        }
        require_once $name_path_script.'middleware.php';
        break;

    case 'model':
        unset($command[0]);
        $command = array_values($command);
        $name_model = $command[0] ?? '';
        if(empty($name_model)) {
            echo 'The model is not null';
            exit();
        }
        unset($command[0]);
        $command = array_values($command);
        $name_array_table = $command[0] ?? 'table=default';
        $name_table = explode('=', $name_array_table)[1];
        if(empty($name_table)) {
            echo 'Name table is not null';
            exit();
        }
        require_once $name_path_script.'model.php';
        break;

    case 'view':
        unset($command[0]);
        $name_view = array_values($command)[0] ?? '';
        if(empty($name_view)) {
            echo 'The view is not null';
            exit();
        }
        require_once $name_path_script.'view.php';
        break;

    case 'queue_job':
        unset($command[0]);
        $command = array_values($command);
        $name_job = $command[0] ?? '';
        if(empty($name_job)) {
            echo 'The queue jobs is not null';
            exit();
        }
        require_once $name_path_script.'jobs.php';
        break;

    case 'queue':
        require_once 'core/function.php';
        require_once 'config/constant.php';
        require_once 'config/database.php';
        require_once 'vendor/autoload.php';
        unset($command[0]);
        $command = array_values($command);
        $type_queue = $command[0] ?? '';
        if(empty($type_queue)) {
            echo 'The queue is not null';
            exit();
        }
        unset($command[0]);
        $type_queue2 = array_values($command)[0] ?? '';
        $job_queue = 'job';
        if(!empty($type_queue2) && $type_queue2 === 'rollback_failed_job') {
            $job_queue = 'job_failed';
        }
        require_once $name_path_script.'queue.php';
        break;

    case 'request':
        unset($command[0]);
        $command = array_values($command);
        $name_request = $command[0] ?? '';
        if(empty($name_request)) {
            echo 'The request is not null';
            exit();
        }
        require_once $name_path_script.'request.php';
        break;
}