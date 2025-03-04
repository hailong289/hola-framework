<?php
namespace App\Http\Middleware;

class Kernel {
    public $routerMiddleware = [
        "auth" => \App\Http\Middleware\AuthMiddleware::class
    ];

    /**
     * @var string[] $requireMiddleware
     * Require middleware for all router
     * Turn off if you use tokens or JWT to authenticate or other methods
     * comment this line if you don't want to use CSRF token
     */
    public $requireMiddleware = [
        \App\Http\Middleware\VerifyCsrfToken::class
    ];

    public function getMiddleware() {
        return $this->routerMiddleware;
    }

    public function getRequireMiddleware() {
        return $this->requireMiddleware;
    }


}