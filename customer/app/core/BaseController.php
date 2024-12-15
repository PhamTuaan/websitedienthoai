<?php
class BaseController
{
    protected function model($model) {
        $modelFile = "./app/models/" . $model . ".php";
        if (!file_exists($modelFile)) {
            throw new Exception("Model not found: " . $model);
        }
        require_once $modelFile;
        return new $model;
    }

    protected function view($view, $data = []) {
        extract($data);
        require_once './app/core/function.php';
        $func = new Func();
        $func->setRootPath();

        $viewFile = "./app/views/layouts/" . $view . ".php";
        if (!file_exists($viewFile)) {
            throw new Exception("View not found: " . $view);
        }
        require_once $viewFile;
    }

    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        header("Location: " . URL_APP . "/" . $url);
        exit;
    }

    
}
