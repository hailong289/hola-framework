<?php
use Hola\Core\Router;
use App\Controllers\HomeController;
Router::get('/', [HomeController::class,'index']);