<?php
class Func
{
    private $url; // Biến lưu trữ URL phân tách từ chuỗi yêu cầu.

    // Constructor - Khởi tạo đối tượng, phân tách URL từ tham số 'url' trong request.
    public function __construct()
    {
        // Kiểm tra nếu tham số 'url' tồn tại trong request.
        if (isset($_REQUEST['url'])) {
            // Tách chuỗi URL thành mảng bằng dấu '/' và loại bỏ khoảng trắng thừa.
            $this->url = explode('/', filter_var(trim($_REQUEST['url'], '/')));
        }
    }

    // Phương thức này trả về mảng URL đã được phân tách.
    public function getUrl()
    {
        return $this->url; // Trả về giá trị của biến $url.
    }

    // Phương thức này dùng để xử lý và xác định trạng thái "active" cho menu.
    function handleActive($name)
    {
        $active = ''; // Biến lưu trạng thái 'active' (chưa được đánh dấu).
        $display = ''; // Biến lưu trạng thái 'display' (chưa được đánh dấu).
        
        // Nếu không có URL nào (mảng $this->url rỗng), gán 'active' cho display.
        if (empty($this->url)) {
            $display = 'active'; // Nếu không có URL, mặc định trang đầu tiên được active.
        }

        // Nếu URL có phần tử đầu tiên và nó trùng với tham số $name, đánh dấu 'active'.
        if (isset($this->url[0]) && $this->url[0] == $name) {
            $active = 'active'; // Nếu tên URL trùng với tham số, đánh dấu "active".
        }

        // Trả về mảng chứa các trạng thái 'active' và 'display'.
        return ['active' => $active, 'display' => $display];
    }

    // Phương thức này dùng để thiết lập các hằng số liên quan đến đường dẫn gốc của ứng dụng.
    function setRootPath()
    {
        $folder_root = 'phone-ecommerce-chat'; // Đặt tên thư mục gốc của ứng dụng.

        // Nếu hằng số FOLDER_ROOT chưa được định nghĩa, định nghĩa nó.
        if (!defined('FOLDER_ROOT')) {
            define('FOLDER_ROOT','/'. $folder_root); // Đường dẫn gốc thư mục
        }

        // Nếu hằng số SCRIPT_ROOT chưa được định nghĩa, định nghĩa nó.
        if (!defined('SCRIPT_ROOT')) {
            define('SCRIPT_ROOT', 'http://localhost/'.$folder_root.'/admin/public'); // Đường dẫn gốc cho script của ứng dụng.
        }

        // Nếu hằng số IMAGES_PATH chưa được định nghĩa, định nghĩa nó.
        if (!defined('IMAGES_PATH')) {
            define('IMAGES_PATH', 'http://localhost/'.$folder_root.'/storages/public'); // Đường dẫn gốc cho thư mục chứa hình ảnh.
        }

        // Nếu hằng số URL_APP chưa được định nghĩa, định nghĩa nó.
        if (!defined('URL_APP')) {
            define('URL_APP', 'http://localhost/'.$folder_root.'/admin'); // Đường dẫn URL cho admin panel của ứng dụng.
        }
    }
}