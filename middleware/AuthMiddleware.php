<?php
namespace Middleware;
use Hola\Core\Request;
use Hola\Core\Response;

class AuthMiddleware {
    // return boolean function
     public function handle(Request $request, Response $response){
         $request->set('name', 1);
         if(!$request->session('is_login')){
            return $response->close('Login does not exist');
         }
         return $response->next($request);
     }
}