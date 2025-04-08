<?php
namespace App\Http\Middleware;
use Hola\Transport\Middleware;
use Hola\Transport\Request;
use Hola\Transport\Response;

class AuthMiddleware extends Middleware {
     public function forward(Request $request, \Closure $continue){
         return $continue($request);
     }
}