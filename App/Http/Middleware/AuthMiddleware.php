<?php
namespace App\Http\Middleware;
use Hola\Transport\Middleware;
use Hola\Transport\Request;
use Hola\Transport\Response;

class AuthMiddleware extends Middleware {
    // return boolean function
     public function handle(Request $request, Response $response){
         return $response::next($request);
     }
}