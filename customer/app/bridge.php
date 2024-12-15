<?php
if (!defined('BASE_URL')) {
    define('ROOT_PATH', '');
    define('BASE_URL', '/phone-ecommerce-chat/customer');
    define('ASSETS_URL', BASE_URL . '/public/assets');
    define('STORAGE_URL', '/phone-ecommerce-chat/storages/public');
}

require_once './app/core/DataBase.php';
require_once './app/core/BaseModel.php';
require_once './app/core/BaseController.php';
require_once './app/core/App.php';