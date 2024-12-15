<?php
class ProductsController extends BaseController
{

    private $productModel;
    private $categoryModel;

    /**
     * Khởi tạo controller, tạo các đối tượng model cần thiết.
     */
    public function __construct()
    {
        $this->productModel = $this->model('ProductModel');
        $this->categoryModel = $this->model('CategoryModel');
    }

    /**
     * Hiển thị trang danh sách sản phẩm.
     * Sử dụng view 'app' với trang 'products/index' và tiêu đề 'Sản phẩm'.
     */
    public function index()
    {
        $this->view(
            'app',
            [
                'page' => 'products/index',
                'title' => 'Sản phẩm',
            ]
        );
    }

    /**
     * Lấy tất cả sản phẩm và trả về dưới dạng JSON.
     * Thiết lập header trả về kiểu nội dung là JSON.
     */
    public function all()
    {
        $products = $this->productModel->getProducts();
        $result = [
            'status' => 200,
            'data' => $products
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Hiển thị trang tạo sản phẩm mới.
     * Lấy danh sách các danh mục từ model và truyền vào view.
     */
    public function create()
    {
        $categories = $this->categoryModel->getCategoriesByStatusTrue();
        $this->view(
            'app',
            [
                'page' => 'products/create',
                'title' => 'Sản phẩm',
                'categories' => $categories,
            ]
        );
    }

    /**
     * Xử lý lưu sản phẩm mới từ dữ liệu gửi lên từ form.
     * Kiểm tra dữ liệu đầu vào, xử lý tải lên hình ảnh và lưu sản phẩm.
     * Trả về kết quả dưới dạng JSON.
     */
    public function store()
    {
        $productName = $_POST['product_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $catId = $_POST['category_id'];

        if (!$productName || !$price || !$quantity || !$description || !$catId) {
            $_SESSION['errors']['product_name'] = 'Thiếu thông tin bắt buộc';
            header('Location: /phone-ecommerce-chat/admin/products/create');
            exit();
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = md5(uniqid()) . basename($_FILES['image']['name']);

                if (move_uploaded_file($_FILES['image']['tmp_name'], '../storages/public/product_images/' . $fileName)) {
                } else {
                    // Xử lý lỗi lưu trữ hình ảnh
                    $_SESSION['errors']['product_name'] = 'Không thể tải lên hình ảnh';
                    header('Location: /phone-ecommerce-chat/admin/products/create');
                }
            } else {
                // Xử lý lỗi định dạng hình ảnh không hợp lệ
                $_SESSION['errors']['product_name'] = 'Định dạng hình ảnh không hợp lệ';
                exit();
            }
        }

        $data = [
            'product_name' => $productName,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description,
            'image' => 'product_images/' . $fileName,
            'category_id' => $catId,
        ];

        $this->productModel->createProduct($data);

        $result = [
            'status' => 200,
            'message' => 'Sản phẩm đã được tạo thành công'
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Hiển thị trang chỉnh sửa sản phẩm.
     * Lấy thông tin sản phẩm và danh mục từ model để hiển thị.
     * Nếu sản phẩm không tồn tại, chuyển hướng đến trang danh mục.
     */
    public function edit($id)
    {
        $product = $this->productModel->getProduct($id);
        $categories = $this->categoryModel->getCategoriesByStatusTrue();

        if (!$product) {
            $_SESSION['success'] = 'Không tìm thấy danh mục';
            header('Location: /phone-ecommerce-chat/admin/categories/index');
        }

        $this->view(
            'app',
            [
                'page' => 'products/edit',
                'title' => 'Thống kê',
                'product' => $product,
                'categories' => $categories
            ]
        );
    }

    public function update($id)
    {
        try {
            $existingProduct = $this->productModel->getProduct($id);
            if (!$existingProduct) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy sản phẩm'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            if ($existingProduct['deleted_by'] !== null) {
                $result = [
                    'status' => 403,
                    'message' => 'Không thể cập nhật sản phẩm đang bị vô hiệu hoá'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }
            $productName = $_POST['product_name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $catId = $_POST['category_id'];

            if (!$productName || !$price || !$quantity || !$description || !$catId) {
                $result = [
                    'status' => 404,
                    'message' => 'Thiếu thông tin bắt buộc'
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            $fileName = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = md5(uniqid()) . basename($_FILES['image']['name']);

                    if (move_uploaded_file($_FILES['image']['tmp_name'], '../storages/public/product_images/' . $fileName)) {
                    } else {
                        $_SESSION['errors']['product_name'] = 'Không thể tải lên hình ảnh';
                        header('Location: /phone-ecommerce-chat/admin/products/create');
                    }
                } else {
                    $_SESSION['errors']['product_name'] = 'Định dạng hình ảnh không hợp lệ';
                    exit();
                }
            }

            $oldFileName = null;
            if (empty($fileName)) {
                $existingProduct = $this->productModel->getProduct($id);
                $oldFileName = $existingProduct['image'];
            }

            $data = [
                'product_name' => $productName,
                'price' => $price,
                'quantity' => $quantity,
                'description' => $description,
                'image' => $oldFileName != null ? $oldFileName : 'product_images/' . $fileName,
                'category_id' => $catId,
            ];

            $this->productModel->updateProduct($id, $data);

            $result = [
                'status' => 200,
                'message' => 'Sản phẩm đã được cập nhật thành công'
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => 'Có lỗi xảy ra: ' . $th->getMessage()
            ];
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Xóa một sản phẩm dựa trên ID.
     * Kiểm tra sự tồn tại của sản phẩm trước khi xóa.
     * Chuyển hướng đến trang danh sách sản phẩm sau khi xóa.
     */
    public function destroy($id)
    {
        try {

            $product = $this->productModel->getProduct($id);

            if (!$product) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy sản phẩm'
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                exit();
            }

            $this->productModel->deleteProduct($id);

            $result = [
                'status' => 204,
                'message' => 'Xóa sản phẩm thành công'
            ];

            header('Location: /phone-ecommerce-chat/admin/products');

            // header('Content-Type: application/json');
            // echo json_encode($result);
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }
}
