<?php
use App\Core\Router;

class ConfigRouter extends Router {
    protected $default = 'web';

    public function __construct()
    {
        require_once "index.php";
    }

    public function loadFile($name) {
        if(file_exists("router/$name.php")) {
            require_once "index.php";
        } else {
            http_response_code(500);
            die("File $name in router does not exit");
        }
        self::$path_load_file = '';
        return $this;
    }

    public function add($name) {
        self::$path_load_file = '/'. $name;
        return $this;
    }

}


$configRouter = new ConfigRouter();
$configRouter->add('api')->loadFile('api');
