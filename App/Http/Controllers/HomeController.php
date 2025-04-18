<?php
namespace App\Http\Controllers;
use App\QueueJobs\Job1;
use Hola\Transport\Request;

class HomeController {
    public function __construct() {}
    public function index(Request $request){
        return res()->view('welcome');
    }

}