<?php
namespace App;
use System\Core\Request;
use System\Core\Response;
use System\Core\Router;

class App {
    private $__controller;
    private $__action;
    private $__param = [];
    private $router;
    public function __construct(){}
    private function handleUrl(){
        try {
            $url = $this->router->url();
            $urlarr = array_values($url);
            if (!empty($urlarr[0])) {
                $this->__controller = $urlarr[0];
                if (class_exists($this->__controller)) {
                    $__controller = new \ReflectionClass($this->__controller);
                    $params = $__controller->getConstructor()->getParameters();
                    $agr = [];
                    foreach ($params AS $param) {
                        $class = $param->getClass()->name;
                        if (!empty($class)) {
                            array_push($agr, new $class());
                        }
                    }
                    $this->__controller = new $this->__controller(...$agr);
                } else {
                    throw new \RuntimeException("{$this->__controller} does not exit", 500);
                }
                unset($urlarr[0]);
            } else {
                throw new \RuntimeException('Page not found', 404);
            }
            if (isset($urlarr[1])) {
                $this->__action = $urlarr[1];
                unset($urlarr[1]);
            }
            $this->__param = array_values($urlarr);
            if (method_exists($this->__controller, $this->__action)) {
                // xử lý method
                $method = new \ReflectionMethod($this->__controller, $this->__action);
                $agr = [];
                foreach ($method->getParameters() as $ag){
                    $class = $ag->getClass()->name;
                    if (!empty($class)) {
                        array_push($agr, new $class());
                    }
                }
                foreach ($this->__param as $value) {
                    array_push($agr, $value);
                }
                $result = $this->__controller->{$this->__action}(...$agr);
                return [
                    "error_code" => 0,
                    "return" => $result
                ];
            }else{
                $controller = serialize($this->__controller);
                throw new \RuntimeException("Method {$this->__action} does not exit in controller {$controller}",500);
            }
        }catch (\Throwable $e){
            $code = (int)$e->getCode();
            $code = $code ? $code : 500;
            $enable_db = config_env('DEBUG_LOG',false);
            if ($enable_db) {
                $this->write_logs_error($e);
            }
            return [
                "error_code" => 1,
                "return" => [
                    "message" => $e->getMessage(),
                    "line" => $e->getLine(),
                    "file" => $e->getFile(),
                    "trace" => $e->getTraceAsString(),
                    "code" => $code
                ],
                "view" => "error.index"
            ];
        }
    }
    public function run() {
        try {
            date_default_timezone_set(TIMEZONE);
            $this->router = new Router();
            $is_api = (new Request())->isJson();
            $resultHandle = $this->handleUrl();
            if($resultHandle['error_code'] === 0){
                if(is_array($resultHandle['return']) || is_object($resultHandle['return'])) {
                    echo json_encode($resultHandle['return']);
                } elseif (!is_file($resultHandle['return'])) {
                    http_response_code(200);
                    echo $resultHandle['return'];
                }
                return $this;
            }
            if ($resultHandle['error_code'] === 1) {
                $code = (int)($resultHandle["return"]["code"] ?? 500);
                if($is_api) {
                    http_response_code($code);
                    echo json_encode($resultHandle['return']);
                    return $this;
                }
                return Response::view($resultHandle['view'], $resultHandle['return'], $code);
            }
        }catch (\Throwable $e) {
            throw $e;
        }
    }

    private function write_logs_error($e): void {
        $date = "[".date('Y-m-d H:i:s')."]: ";
        if (!file_exists(__DIR__ROOT .'/storage')) {
            if (!mkdir($concurrentDirectory = __DIR__ROOT . '/storage', 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        file_put_contents(__DIR__ROOT .'/storage/debug.log',$date . $e . PHP_EOL.PHP_EOL, FILE_APPEND);
    }
}