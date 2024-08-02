<?php
namespace App;
use Hola\Application;

class App extends Application {

   public function register()
   {
       // dependency injection
       // $this->set(\App\Repositories\Blog\BlogInterface::class, \App\Repositories\Blog\BlogRepository::class);
   }

}