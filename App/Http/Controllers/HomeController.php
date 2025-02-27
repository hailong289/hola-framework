<?php
namespace App\Http\Controllers;
use App\QueueJobs\Job1;
use Hola\Transport\Request;

class HomeController {
    public function __construct() {}
    public function index(Request $request){
        sendJobs(new Job1(2,3));
        return res()->view('welcome');
    }

}