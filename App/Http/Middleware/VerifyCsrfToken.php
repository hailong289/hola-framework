<?php

namespace App\Http\Middleware;

use Hola\Core\MiddlewareCore;

class VerifyCsrfToken extends MiddlewareCore {
    /**
     * @var string[] $except
     * Do not check CSRF token with these paths
     */
     public $except = [];

}