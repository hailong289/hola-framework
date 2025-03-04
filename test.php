<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    if (file_exists('vendor/autoload.php')) {
        require 'vendor/autoload.php';
    } else {
        throw new \Exception("File vendor/autoload.php không tồn tại!");
    }
    if (file_exists('bootstrap.php')) {
        require_once "bootstrap.php";
    } else {
        throw new \Exception("File bootstrap.php không tồn tại!");
    }

    $app = new App\App();
    $app->testRun();
} catch (\Throwable $e) {
    http_response_code(500);
    echo "Lỗi: " . $e->getMessage();
}
exit();