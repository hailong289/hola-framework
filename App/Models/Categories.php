<?php
namespace App\Models;
use Hola\DataBase\Model;
use Hola\Core\Redis;

class Categories extends Model {
    protected static $table = 'category';
    protected static $time_auto = false;
    protected static $date_create = "date_created";
    protected static $date_update = "date_updated";
    protected static $field = [
        'name',
        'view'
    ];
    protected static $hiddenField = [
        'invalid',
    ];

    public function setAttributeName($value){
        return $value;
    }

    public function getAttributeName($value) {
        return $value;
    }


    public static function index(){}

    public static function store(){
        echo 'store';
    }
}