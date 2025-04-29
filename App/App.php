<?php
namespace App;
use Hola\Application;
use Hola\Views\ViewRender;

class App extends Application {

   public function register()
   {
       // dependency injection
       // $this->set(\App\Repositories\Blog\BlogInterface::class, \App\Repositories\Blog\BlogRepository::class);
       ViewRender::cacheFileHtml([
           'welcome'
       ]);
   }

}