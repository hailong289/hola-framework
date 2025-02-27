<?php
namespace App\Http\Middleware;
use Hola\Core\Middleware as MiddlewareCore;
use Hola\Transport\Request;
use Hola\Transport\Response;

class AuthMiddleware extends MiddlewareCore {
    // return boolean function
     public function handle(Request $request, Response $response){
         return $response::next($request);
     }
}