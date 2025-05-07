<?php
namespace App\Http\Middleware;
use Hola\Transport\Middleware;

class CorsMiddleware extends Middleware {
    /*
     * CORS configuration
     * @var array
     */
    protected array $config = [
        'paths' => ['*'],
        'allowed_methods' => ['*'],
        'allowed_origins' => ['*'],
        'allowed_origins_patterns' => [],
        'allowed_headers' => ['*'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => true,
    ];
}
