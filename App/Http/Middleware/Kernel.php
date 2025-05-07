<?php
namespace App\Http\Middleware;
use Hola\Transport\Interface\IKernel;
class Kernel implements IKernel {
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
        \App\Http\Middleware\CorsMiddleware::class,
        \App\Http\Middleware\VerifyCsrfToken::class
    ];

    public function getMiddlewares() {
        return $this->routerMiddleware;
    }

    public function getRequiredMiddleWares() {
        return $this->requireMiddleware;
    }


}