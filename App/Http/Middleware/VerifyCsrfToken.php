<?php

namespace App\Http\Middleware;
use Hola\Transport\Middleware;

class VerifyCsrfToken extends Middleware {
    /**
     * @var string[] $except
     * Do not check CSRF token with these paths
     */
     public $except = [];
}