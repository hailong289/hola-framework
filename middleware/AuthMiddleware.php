<?php
namespace Middleware;
use Hola\Core\Request;
use Hola\Core\Response;

class AuthMiddleware {
    // return boolean function
     public function handle(Request $request, Response $response){
         return $response->next($request);
     }
}