<?php
class CategoriesController extends BaseController
{

    private $categoryModel;

    /**
     * Lớp CategoriesController này giúp quản lý các thao tác liên quan
     *  đến danh mục như xem, tạo, cập nhật và xóa. 
     */
    /**
     * Khởi tạo controller và liên kết với model CategoryModel.
     */
    public function __construct()
    {
        $this->categoryModel = $this->model('CategoryModel');
    }

    /**
     * Hiển thị trang danh sách các danh mục.
     * Trang được render là 'categories/index' với tiêu đề 'Danh mục'.
     */
    public function index()
    {
        $this->view(
            'app',
            [
                'page' => 'categories/index',
                'title' => 'Danh mục',
            ]
        );
    }

    /**
     * Lấy tất cả danh mục và trả về dưới dạng JSON.
     * Kết quả trả về bao gồm trạng thái HTTP và dữ liệu danh mục.
     */
    public function all()
    {
        $categories = $this->categoryModel->getCategories();


        $result = [
            'status' => 200,
            'data' => $categories
        ];
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    /**
     * Phương thức activeCategories lấy các danh mục đang hoạt động từ
     *  mô hình CategoryModel và trả về dưới dạng JSON với trạng thái HTTP 200.
     */
    public function activeCategories()
    {
        $categories = $this->categoryModel->getCategoriesByStatusTrue();

        $result = [
            'status' => 200,
            'data' => $categories
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Hiển thị trang tạo danh mục mới.
     * Trang được render là 'categories/create' với tiêu đề 'Thống kê'.
     */
    public function create()
    {
        $this->view(
            'app',
            [
                'page' => 'categories/create',
                'title' => 'Thống kê'
            ]
        );
    }

    /**
     * Xử lý việc tạo danh mục mới từ dữ liệu gửi lên.
     * Nếu tên danh mục được cung cấp, danh mục sẽ được tạo và trả về kết quả thành công.
     * Nếu không, trả về lỗi với thông báo yêu cầu tên danh mục.
     */
    public function store()
    {
        try {
            $categoryName = $_POST['category_name'];

            if ($categoryName) {

                $data = ['category_name' => $categoryName];

                $this->categoryModel->createCategory($data);

                $_SESSION['success'] = 'Tạo danh mục thành công';
                $result = [
                    'status' => 201,
                    'message' => "Tạo danh mục thành công"
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                // $_SESSION['category_name'] = '';
                $result = [
                    'status' => 500,
                    'message' => "Vui lòng cung cấp tên danh mục"
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            }
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }

    /**
     * Hiển thị trang chỉnh sửa cho danh mục cụ thể.
     * Phương thức edit nhận ID danh mục và lấy thông tin danh mục từ mô hình CategoryModel.
     * Nếu danh mục không tồn tại, chuyển hướng người dùng và thông báo lỗi.
     * Nếu tồn tại, hiển thị trang 'categories/edit' với thông tin danh mục.
     */
    public function edit($id)
    {
        $category = $this->categoryModel->getCategory($id);
        if (!$category) {
            $_SESSION['success'] = 'Không tìm thấy danh mục';
            header('Location: /phone-ecommerce-chat/admin/categories');
        }

        $this->view(
            'app',
            [
                'page' => 'categories/edit',
                'title' => 'Thống kê',
                'category' => $category,
            ]
        );
    }

    /**
     * Cập nhật thông tin danh mục dựa trên ID và dữ liệu gửi lên.
     * Nếu tên danh mục được cung cấp, cập nhật và trả về kết quả thành công.
     * Nếu không, trả về lỗi với thông báo yêu cầu tên danh mục.
     */
    public function update($id)
    {
        try {
            $categoryName = $_POST['category_name'];

            if ($categoryName) {

                $data = ['category_name' => $categoryName];

                $this->categoryModel->updateCategory($id, $data);

                $result = [
                    'status' => 200,
                    'message' => "Cập nhật danh mục thành công"
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                $_SESSION['category_name'] = 'Vui lòng cung cấp tên danh mục';
                $result = [
                    'status' => 201,
                    'message' => "Cập nhật danh mục thất bại"
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            }
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }

    /**
     * Xóa danh mục dựa trên ID.
     * Nếu danh mục không tồn tại, trả về lỗi không tìm thấy.
     * Nếu tồn tại, xóa danh mục và chuyển hướng người dùng về trang danh sách danh mục.
     */
    public function destroy($id)
    {
        try {
            $category = $this->categoryModel->getCategory($id);

            if (!$category) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy danh mục'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            $this->categoryModel->deleteCategory($id);

            $result = [
                'status' => 204,
                'message' => "Xóa danh mục thành công"
            ];

            header('Location: /phone-ecommerce-chat/admin/categories');

            // header('Content-Type: application/json');
            // echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => "Xóa danh mục thất bại"
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }
}
