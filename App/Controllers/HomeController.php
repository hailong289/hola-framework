<?php
namespace App\Controllers;
use System\Core\BaseController;
use System\Core\Request;
use System\Core\Response;
use System\Queue\CreateQueue;
use Queue\Jobs\Job1;

class HomeController extends BaseController {
    public function __construct()
    {}

    public function index(Request $request){
        (new CreateQueue())->enQueue(new Job1(5,4));
        return Response::view('welcome');
    }

}