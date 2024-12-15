<?php
class Func
{

    private $url;


    public function __construct()
    {
        if (isset($_REQUEST['url'])) {
            $this->url =  explode('/', filter_var(trim($_REQUEST['url'], '/')));
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    function handleActive($name)
    {
        if (empty($this->url)) {
            $display = 'active';
        }
        if ($this->url[0] == $name) {
            $active = 'active';
        }

        return ['active' => $active, 'display' => $display];
    }

    function setRootPath() {
        if (!defined('SCRIPT_ROOT')) {
            define('SCRIPT_ROOT', 'http://localhost/phone-ecommerce-chat/customer/public');
        }
        if (!defined('IMAGES_PATH')) {
            define('IMAGES_PATH', 'http://localhost/phone-ecommerce-chat/storages/public');
        }
        if (!defined('URL_APP')) {
            define('URL_APP', 'http://localhost/phone-ecommerce-chat/customer');
        }
    }
}
