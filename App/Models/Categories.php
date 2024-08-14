<?php
namespace App\Models;
use Hola\Core\Model;
use Hola\Core\Redis;

class Categories extends Model {
    protected static $tableName = 'categories';
    protected static $times_auto = false;
    protected static $date_create = "date_created";
    protected static $date_update = "date_update";
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