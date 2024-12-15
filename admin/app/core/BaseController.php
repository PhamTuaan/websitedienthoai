<?php
class BaseController
{
    // Phương thức này dùng để yêu cầu (include) và tạo đối tượng model.
    public function model($model)
    {
        // Yêu cầu (include) file model tương ứng từ thư mục models
        require_once "./app/models/" . $model . ".php";
        // Tạo và trả về một đối tượng của model.
        return new $model;
    }

    // Phương thức này dùng để yêu cầu (include) view và truyền dữ liệu cho view.
    public function view($view, $data = [])
    {
        // Duyệt qua mảng dữ liệu $data và gán các giá trị vào các biến riêng biệt.
        foreach ($data as $key => $value) {
            $$key = $value; // $$key sẽ tạo một biến với tên là $key và gán giá trị $value cho nó.
        }
        // Yêu cầu (include) file chứa các hàm chức năng.
        require_once './app/core/function.php';
        $func = new Func;  // Khởi tạo đối tượng của lớp Func (giả sử là lớp chứa các hàm tiện ích).
        $func->setRootPath(); // Gọi phương thức setRootPath() của lớp Func.

        // Yêu cầu (include) layout view chính.
        require_once "./app/views/layouts/" . $view . ".php";
    }

     // Phương thức bảo vệ này trả về đường dẫn gốc cho ứng dụng.
    protected function getBasePath() 
    {
        // Trả về đường dẫn gốc của ứng dụng cho admin
        return "/phone-ecommerce-chat/admin";
    }
    
}
