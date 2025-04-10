<?php
namespace App\Http\Middleware;
use Hola\Transport\Middleware;
use Hola\Transport\Request;

class AuthMiddleware extends Middleware {
     public function forward(Request $request, \Closure $continue){
         return $continue($request);
     }
}