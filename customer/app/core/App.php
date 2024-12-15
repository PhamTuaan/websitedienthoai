<?php
class App {
    protected $controller = 'HomeController';
    protected $action = 'index';
    protected $params = [];

    function __construct() {
        try {
            // Load function.php first 
            if (!class_exists('Func')) {
                require_once './app/core/function.php';
            }
            
            $func = new Func();
            $func->setRootPath();

            $elementUrlBox = $this->handleUrl();

            // Handle controller
            if (!empty($elementUrlBox[0])) {
                $this->controller = ucfirst(strtolower($elementUrlBox[0])) . "Controller";
                
                if (file_exists('./app/controllers/' . $this->controller . '.php')) {
                    unset($elementUrlBox[0]);
                } else {
                    $this->controller = 'HomeController';
                }
            }

            require_once('./app/controllers/' . $this->controller . '.php');
            $this->controller = new $this->controller;

            // Handle action  
            if (!empty($elementUrlBox[1])) {
                if (method_exists($this->controller, $elementUrlBox[1])) {
                    $this->action = $elementUrlBox[1];
                    unset($elementUrlBox[1]);
                }
            }

            // Handle params
            $this->params = $elementUrlBox ? array_values($elementUrlBox) : [];
            
            call_user_func_array([$this->controller, $this->action], $this->params);

        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: " . BASE_URL . "/error");
            exit;
        }
    }

    function handleUrl() {
        if (isset($_REQUEST['url'])) {
            return explode('/', filter_var(trim($_REQUEST['url'], '/')));
        }
        return ['home']; 
    }
}