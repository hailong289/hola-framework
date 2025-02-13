<?php
namespace App\Controllers;
use App\Models\Categories;
use Hola\Transport\Request;

class HomeController {
    public function __construct() {}
    public function index(Request $request){
        return res()->view('welcome');
    }

}