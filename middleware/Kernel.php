<?php
namespace Middleware;

class Kernel {
    public $routerMiddleware = [
        "auth" => \Middleware\AuthMiddleware::class,
    ];
}