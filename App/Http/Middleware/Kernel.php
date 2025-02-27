<?php
namespace App\Http\Middleware;

class Kernel {
    public $routerMiddleware = [
        "auth" => \App\Http\Middleware\AuthMiddleware::class
    ];
}