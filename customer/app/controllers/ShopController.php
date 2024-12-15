<?php
class ShopController extends BaseController
{
    private $productModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->orderModel = $this->model('OrderModel'); 
    }

    public function index()
    {
        $this->view('app', [
            'page' => 'shop/index',
            'title' => 'Shop',
        ]);
    }

    public function all()
    {
        $products = $this->productModel->getProducts();
        
        if (!$products) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 204,
                'message' => "Lỗi fetch sản phẩm!"
            ]);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 200,
            'message' => "success",
            'data' => $products,
        ]);
    }

    public function detail($id) {
        try {
            $product = $this->productModel->getProduct($id);
            if (!$product) {
                header('Location: ' . URL_APP . '/home');
                return;
            }

            $reviews = $this->productModel->getProductReviews($id);

            $hasPurchased = false;
            if (isset($_SESSION['auth'])) {
                $hasPurchased = $this->productModel->checkProductPurchased(
                    $_SESSION['auth']['customer_id'],
                    $id
                );
            }

            $this->view('app', [
                'page' => 'shop/detail', 
                'title' => $product['product_name'],
                'product' => $product,
                'reviews' => $reviews,
                'hasPurchased' => $hasPurchased
            ]);

        } catch (Exception $e) {
            error_log("Error in product detail: " . $e->getMessage());
            header('Location: ' . URL_APP . '/home');
        }
    }

    public function showProductDetailData($id)
    {
        $product = $this->productModel->getProduct($id);
        
        if (!$product) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 204,
                'message' => "Lỗi fetch sản phẩm!"
            ]);
            return;
        }

        // Get reviews from product_reviews table with customer info
        $reviews = $this->productModel->getProductReviews($id);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 200,
            'message' => "success",
            'data' => $product,
            'reviews' => $reviews
        ]);
    }

    public function getRelatedProducts($id, $categoryId) {
        try {
            $products = $this->productModel->getRelatedProducts($id, $categoryId);
            $this->jsonResponse(200, 'Success', $products);
        } catch (Exception $e) {
            $this->jsonResponse(500, $e->getMessage());
        }
    }

    public function addReview() {
        if (!isset($_SESSION['auth'])) {
            $this->jsonResponse(401, 'Vui lòng đăng nhập');
            return;
        }

        $productId = $_POST['product_id'] ?? null;
        $content = $_POST['content'] ?? '';
        $rate = $_POST['rate'] ?? null;

        if (!$productId || !$content || !$rate) {
            $this->jsonResponse(400, 'Thiếu thông tin đánh giá');
            return;
        }

        try {
            // Kiểm tra đã mua hàng chưa
            $hasPurchased = $this->productModel->checkProductPurchased(
                $_SESSION['auth']['customer_id'],
                $productId
            );

            if (!$hasPurchased) {
                $this->jsonResponse(403, 'Bạn cần mua sản phẩm này để đánh giá');
                return;
            }

            // Kiểm tra đã đánh giá chưa 
            $existingReview = $this->productModel->getUserProductReview(
                $productId,
                $_SESSION['auth']['customer_id']
            );

            if ($existingReview) {
                $this->jsonResponse(403, 'Bạn đã đánh giá sản phẩm này');
                return;
            }

            // Thêm đánh giá mới
            $reviewData = [
                'customer_id' => $_SESSION['auth']['customer_id'],
                'product_id' => $productId,
                'content' => $content,
                'rate' => $rate
            ];

            $this->productModel->createReview($reviewData);
            $this->jsonResponse(200, 'Đã thêm đánh giá thành công');

        } catch (Exception $e) {
            error_log("Error adding review: " . $e->getMessage());
            $this->jsonResponse(500, 'Có lỗi khi thêm đánh giá');
        }
    }

    private function jsonResponse($status, $message, $data = null) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
    public function checkPurchaseHistory($product_id)
    {
        if (!isset($_SESSION['auth'])) {
            $result = [
                'status' => 403,
                'hasBought' => false,
                'message' => 'Vui lòng đăng nhập'
            ];
        } else {
            $customer_id = $_SESSION['auth']['customer_id'];
            $hasBought = $this->productModel->checkProductPurchased($product_id, $customer_id);
            $result = [
                'status' => 200,
                'hasBought' => $hasBought,
                'message' => 'Kiểm tra thành công'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function checkProductReview($product_id) 
    {
        if (!isset($_SESSION['auth'])) {
            $result = [
                'status' => 403,
                'hasReviewed' => false,
                'message' => 'Vui lòng đăng nhập'
            ];
        } else {
            $customer_id = $_SESSION['auth']['customer_id'];
            $review = $this->productModel->getUserProductReview($product_id, $customer_id);
            $result = [
                'status' => 200,
                'hasReviewed' => $review !== null,
                'message' => 'Kiểm tra thành công'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function filterByCategory($id)
    {
        try {
            $products = $this->productModel->getProductsByCategory($id);

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 200,
                'message' => "Tìm sản phẩm theo danh mục thành công",
                'data' => $products
            ]);
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function filterByPrice($minPrice, $maxPrice)
    {
        $minPrice = isset($minPrice) ? str_replace('$', '', $minPrice) : 1000000;
        $maxPrice = isset($maxPrice) ? str_replace('$', '', $maxPrice) : 45000000;

        $products = $this->productModel->getProductsByPrice($minPrice, $maxPrice);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 200,
            'message' => 'Filtered products by price successfully',
            'data' => $products
        ]);
    }

    public function getProductReviews($productId)
    {
        $reviews = $this->productModel->getProductReviews($productId);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 200,
            'message' => "Lấy danh sách đánh giá thành công",
            'data' => $reviews
        ]);
    }

    public function addProductReview()
    {
        if (!isset($_SESSION['auth'])) {
            $result = [
                'status' => 403,
                'message' => 'Vui lòng đăng nhập để đánh giá'
            ];
            header('Content-Type: application/json');
            echo json_encode($result);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $result = [
                'status' => 405,
                'message' => 'Method not allowed'
            ];
            header('Content-Type: application/json');
            echo json_encode($result);
            return;
        }

        $customer_id = $_SESSION['auth']['customer_id'];
        $product_id = $_POST['product_id'];
        $content = $_POST['content'];
        $rate = $_POST['rate'];

        // Kiểm tra xem khách hàng đã mua sản phẩm chưa
        if (!$this->productModel->checkProductPurchased($product_id, $customer_id)) {
            $result = [
                'status' => 403,
                'message' => 'Bạn cần mua sản phẩm này để có thể đánh giá'
            ];
        }
        // Kiểm tra xem đã đánh giá chưa
        else if ($this->productModel->getUserProductReview($product_id, $customer_id)) {
            $result = [
                'status' => 400,
                'message' => 'Bạn đã đánh giá sản phẩm này'
            ];
        }
        // Thêm đánh giá mới
        else {
            $success = $this->productModel->addProductReview($product_id, $customer_id, $content, $rate);
            $result = [
                'status' => $success ? 201 : 500,
                'message' => $success ? 'Đánh giá thành công' : 'Có lỗi xảy ra'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }


    public function updateProductReview()
    {
        if (!isset($_SESSION['customer'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 401,
                'message' => "Vui lòng đăng nhập để cập nhật đánh giá"
            ]);
            return;
        }

        $customerId = $_SESSION['customer']['customer_id'];
        $productId = $_POST['product_id'] ?? null;
        $content = $_POST['content'] ?? null;
        $rate = $_POST['rate'] ?? null;

        if (!$productId || !$content || !$rate) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 400,
                'message' => "Thiếu thông tin cập nhật đánh giá"
            ]);
            return;
        }

        $success = $this->productModel->updateProductReview($customerId, $productId, $content, $rate);
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $success ? 200 : 500,
            'message' => $success ? "Cập nhật đánh giá thành công" : "Lỗi khi cập nhật đánh giá"
        ]);
    }

    public function deleteProductReview($productId)
    {
        if (!isset($_SESSION['customer'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 401,
                'message' => "Vui lòng đăng nhập để xóa đánh giá"
            ]);
            return;
        }

        $customerId = $_SESSION['customer']['customer_id'];
        $success = $this->productModel->deleteProductReview($customerId, $productId);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => $success ? 200 : 500,
            'message' => $success ? "Xóa đánh giá thành công" : "Lỗi khi xóa đánh giá"
        ]);
    }

    public function getUserReview($productId)
    {
        if (!isset($_SESSION['customer'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 401,
                'message' => "Người dùng chưa đăng nhập",
                'status' => false
            ]);
            return;
        }

        $customerId = $_SESSION['customer']['customer_id'];
        $review = $this->productModel->getUserProductReview($productId, $customerId);
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $review ? 200 : 204,
            'message' => $review ? "Lấy đánh giá người dùng thành công" : "Người dùng chưa đánh giá sản phẩm này",
            'data' => $review,
            'status' => (bool)$review
        ]);
    }
}