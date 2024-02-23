<?php
namespace System\Controllers;
use App\Models\Categories;
use System\Core\BaseController;
use System\Core\Database;
use System\Core\Request;
use System\Core\Response;

class HomeController extends BaseController {
    public function __construct()
    {}

    public function index(Request $request){
        return Response::view('welcome');
    }

}