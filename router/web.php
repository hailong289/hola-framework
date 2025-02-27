<?php
use App\Http\Controllers\HomeController;
use Hola\Routings\Router;
Router::get('/', [HomeController::class,'index']);