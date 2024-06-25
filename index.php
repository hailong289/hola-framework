<?php
require 'vendor/autoload.php';
session_start();
use App\App;
use System\Core\Request;
use System\Core\Response;
$is_api = (new Request())->isJson();
if ($is_api) header('Content-Type: application/json; charset=utf-8');
try {
    require_once "bootstrap.php";
    $app = new App();
    $app->run();
} catch (Throwable $e) {
    $code = (int)$e->getCode();
    $date = "[".date('Y-m-d H:i:s')."]: ";
    if (!file_exists(__DIR__ROOT .'/storage')) {
        if (!mkdir($concurrentDirectory = __DIR__ROOT . '/storage', 0777, true) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
    }
    file_put_contents(__DIR__ROOT .'/storage/debug.log',$date . $e . PHP_EOL.PHP_EOL, FILE_APPEND);
    $code = (int)($code ? $code:500);
    if($is_api) {
        echo Response::json([
            "message" => $e->getMessage(),
            "code" => $code,
            "line" => $e->getLine(),
            "file" => $e->getFile(),
            "trace" => $e->getTraceAsString()
        ], $code);
    }
    return Response::view("error.index", [
        "message" => $e->getMessage(),
        "code" => $code,
        "line" => $e->getLine(),
        "file" => $e->getFile(),
        "trace" => $e->getTraceAsString()
    ], $code);
}
