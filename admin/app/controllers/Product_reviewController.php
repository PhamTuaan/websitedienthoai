<?php
// $productModel: Được sử dụng để tương tác với dữ liệu liên quan đến sản phẩm, đặc biệt là đánh giá sản phẩm.
// Phương thức khởi tạo (__construct): Sử dụng phương thức model() để khởi tạo đối tượng
//  của lớp ProductModel và gán nó vào thuộc tính $productModel.
class Product_reviewController extends BaseController
{
    private ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = $this->model('ProductModel');
    }

    // Hiển thị giao diện trang quản lý đánh giá sản phẩm.
    // Gọi phương thức view() để tải giao diện trang: page: Đường dẫn file view (product_reviews/index).
    // title: Tiêu đề trang là "Đánh giá sản phẩm".
    public function index()
    {
        $this->view('app', [
            'page' => 'product_reviews/index',
            'title' => 'Đánh giá sản phẩm',
        ]);
    }

    // Lấy danh sách đánh giá sản phẩm từ cơ sở dữ liệu và trả về dưới dạng JSON.
    public function all() {
        try {
            // Xóa mọi dữ liệu xuất ra trước đó.
            ob_clean();
            
            $result = $this->productModel->getProductReviews(); // Lấy dữ liệu đánh giá sản phẩm từ model.
            
            // Log dữ liệu để kiểm tra.
            error_log('Raw response data: ' . json_encode($result));
            
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-cache, must-revalidate');
            
            // Chuẩn bị phản hồi JSON.
            $response = [
                'data' => $result['data'] ?? [], // Danh sách đánh giá.
                'draw' => isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 1, // Dùng cho DataTables.
                'recordsTotal' => count($result['data'] ?? []), // Tổng số đánh giá.
                'recordsFiltered' => count($result['data'] ?? []) // Tổng số đánh giá (đã lọc nếu có).
            ];
            
            // Ghi lại phản hồi cuối cùng
            error_log('Final JSON response: ' . json_encode($response));
            
            echo json_encode($response, 
                JSON_UNESCAPED_UNICODE | 
                JSON_UNESCAPED_SLASHES | 
                JSON_PARTIAL_OUTPUT_ON_ERROR
            );
            exit; // Dừng thực thi tiếp sau khi phản hồi.
            
        } catch (Exception $e) {
            error_log('Error in all(): ' . $e->getMessage());
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'data' => [],
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'error' => $e->getMessage()
            ]);
            exit;
        }
    }

    // Xóa một đánh giá sản phẩm theo ID.
    public function destroy($id)
    {
        try {
            $result = $this->productModel->deleteProductReview($id); // Xóa đánh giá sản phẩm theo ID.
            if ($result) {
                $_SESSION['success'] = 'Xóa đánh giá thành công';
            } else {
                $_SESSION['error'] = 'Xóa đánh giá thất bại';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
        
        // Chuyển hướng về trang quản lý đánh giá.
        header('Location: ' . $this->getBasePath() . '/product_review');
        exit;
    }
}