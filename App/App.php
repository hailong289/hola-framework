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
                    "return" => $result,
                    "status_code" => 200
                ];
            }else{
                $controller = serialize($this->__controller);
                throw new \RuntimeException("Method {$this->__action} does not exit in controller {$controller}",500);
            }
        }catch (\Throwable $e){
            $this->write_logs_error($e);
            $code = (int)$e->getCode();
            $code = $code ? $code : 500;
            return [
                "error_code" => 1,
                "return" => [
                    "message" => $e->getMessage(),
                    "line" => $e->getLine(),
                    "file" => $e->getFile(),
                    "trace" => $e->getTraceAsString(),
                    "code" => $code
                ],
                "status_code" => $code,
                "view" => "error.index"
            ];
        }
    }
    public function run() {
        $is_api = Request::instance()->isJson();
        if ($is_api) {
            header('Content-Type: application/json; charset=utf-8');
        }
        try {
            $timezone = config_env('TIMEZONE', 'Asia/Ho_Chi_Minh');
            date_default_timezone_set($timezone);
            $this->router = new Router();
            $is_api = (new Request())->isJson();
            $resultHandle = $this->handleUrl();
            $error_code = $resultHandle['error_code'];
            $view = $resultHandle['view'];
            $return = $resultHandle['return'];
            $code = (int)($resultHandle["status_code"] ?? 500);
            if($error_code === 0){
                return $this->buildResponse($return, $code);
            } else {
                return $this->buildResponseError($view, $return, $code, $is_api);
            }
        }catch (\Throwable $e) {
            $this->write_logs_error($e);
            $code = (int)$e->getCode();
            $code = $code ? $code : 500;
            $view = 'error.index';
            $errors = [
                "message" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
                "trace" => $e->getTraceAsString(),
                "code" => $code
            ];
            return $this->buildResponseError($view, $errors, $code, $is_api);
        }
    }

    private function buildResponse($return, $code)
    {
        if(is_array($return) || is_object($return)) {
            echo json_encode($return);
        } else if (is_file($return)) { // return file
            return $this;
        } else {
            http_response_code($code);
            echo $return;
        };
        return $this;
    }

    private function buildResponseError($view, $return, $code, $is_api = false)
    {
        if($is_api) {
            echo json_encode($return);
            return $this;
        }
        return Response::view($view, $return, [], $code);
    }

    private function write_logs_error($e): void {
        $enable_db = config_env('DEBUG_LOG',false);
        if (!$enable_db) return;
        $date = "[".date('Y-m-d H:i:s')."]: ";
        if (!file_exists(__DIR__ROOT .'/storage')) {
            if (!mkdir($concurrentDirectory = __DIR__ROOT . '/storage', 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        file_put_contents(__DIR__ROOT .'/storage/debug.log',$date . $e . PHP_EOL.PHP_EOL, FILE_APPEND);
    }
}