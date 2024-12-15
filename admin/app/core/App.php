<?php
class App
{
    protected $controller = 'HomeController'; // Khởi tạo controller mặc định là 'HomeController'
    protected $action = 'index'; // Khởi tạo action mặc định là 'index'
    protected $params = []; // Khởi tạo mảng params rỗng

    function __construct()
    {
         // Gọi phương thức handleUrl để phân tích URL từ yêu cầu
        $elementUrlBox = $this->handleUrl();

        // Phân tích controller từ URL
        if (!empty($elementUrlBox[0])) { // Kiểm tra nếu có phần tử đầu tiên trong URL
             // Chuyển phần tử đầu tiên thành tên controller, viết hoa chữ cái đầu
            $this->controller = ucfirst(strtolower($elementUrlBox[0])) . "Controller";
           
            // Kiểm tra xem controller tương ứng có tồn tại không
            if (file_exists('./app/controllers/' . $this->controller . '.php')) {
                // Nếu tồn tại, loại bỏ phần tử đầu tiên (controller) khỏi mảng URL
                unset($elementUrlBox[0]);
            } else {
                // Nếu không tồn tại, sử dụng controller mặc định
                $this->controller = 'HomeController';
            }
        }

         // Yêu cầu file controller tương ứng
        require_once('./app/controllers/' . $this->controller . '.php');

        // Phân tích action từ URL
        if (!empty($elementUrlBox[1])) {
            // Kiểm tra xem phương thức (action) có tồn tại trong controller không
            if (method_exists($this->controller, $elementUrlBox[1])) {
                // Nếu có, gán tên action cho biến $this->action
                $this->action = $elementUrlBox[1];
                // Loại bỏ phần tử thứ hai (action) khỏi mảng URL
                unset($elementUrlBox[1]);
            }
        }

        //  Phân tích tham số từ URL (nếu có)
        $this->params = $elementUrlBox ? array_values($elementUrlBox) : [];
        // Nếu mảng URL còn phần tử nào, thì coi đó là tham số và lưu vào $this->params
        
        // Khởi tạo đối tượng controller
        $this->controller = new $this->controller;
        // Gọi phương thức action của controller với các tham số
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    // Phương thức phân tích URL và trả về mảng các phần tử URL
    function handleUrl()
    {
        if (isset($_REQUEST['url'])) { // Kiểm tra nếu có URL trong yêu cầu
            // Loại bỏ khoảng trắng thừa và tách URL thành các phần tử
            return explode('/', filter_var(trim($_REQUEST['url'], '/')));
        }
    }
}
