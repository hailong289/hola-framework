<?php
namespace App\Controllers;
use App\Models\Categories;
use Hola\Core\BaseController;
use Hola\Core\Request;
use Hola\Core\Response;
use Hola\Queue\CreateQueue;
use Queue\Jobs\Job1;

class HomeController extends BaseController {
    public function __construct()
    {}

    public function index(Request $request) {
        return Response::view('welcome');
    }

}