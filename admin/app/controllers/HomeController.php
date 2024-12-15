<?php
/**
 * Controller này quản lý các thao tác liên quan đến trang chủ và các thống kê
 * như doanh thu hôm nay, tổng doanh thu, tổng số người dùng, tổng số khách hàng,
 * tổng số sản phẩm, danh sách đơn hàng hôm nay, danh sách sản phẩm bán chạy, và dữ liệu biểu đồ. 
 */
class HomeController extends BaseController
{

    private $productModel;
    private $orderModel;
    private $customerModel;
    private $userModel;

    public function __construct()
    {
        $this->productModel = $this->model('ProductModel');
        $this->orderModel = $this->model('OrderModel');
        $this->customerModel = $this->model('CustomerModel');
        $this->userModel = $this->model('UserModel');
    }

    /**
     * Phương thức index kiểm tra xem người dùng đã đăng nhập hay chưa thông qua biến $_SESSION['auth_admin'].
     * Nếu chưa đăng nhập, người dùng sẽ được chuyển hướng tới trang đăng nhập.
     * Lấy dữ liệu thống kê từ các mô hình:
     * getRevenueToday: lấy doanh thu hôm nay.
     * getTotalRevenue: lấy tổng doanh thu.
     * getTotalUser: lấy tổng số người dùng.
     * getTotalCustomer: lấy tổng số khách hàng.
     * getTotalProduct: lấy tổng số sản phẩm.
     * Gọi phương thức view để hiển thị trang thống kê (dashboard) với các dữ liệu đã lấy.
     */
    public function index()
    {
        if (!isset($_SESSION['auth_admin'])) {
            header('Location: /phone-ecommerce-chat/customer/auth/login');
            return;
        }

        $revenueToday = $this->orderModel->getRevenueToday();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $totalUsers = $this->userModel->getTotalUser();
        $totalCustomers = $this->customerModel->getTotalCustomer();
        $totalProducts = $this->productModel->getTotalProduct();

        $this->view(
            'app',
            [
                'page' => 'dashboard',
                'title' => 'Thống kê',
                'revenueToday' => $revenueToday[0],
                'totalRevenue' => $totalRevenue[0],
                'totalUsers' => $totalUsers,
                'totalCustomers' => $totalCustomers,
                'totalProducts' => $totalProducts
            ]
        );
    }

    /**
     * Phương thức getOrderToday lấy danh sách các đơn hàng hôm nay từ mô hình OrderModel.
     */
    public function getOrderToday()
    {
        try {
            $orderToday = $this->orderModel->getTransactionToday();

            $result = [
                'status' => 200,
                'message' => 'Get Success',
                'data' => $orderToday,
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 404,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức getBestSellerProducts lấy danh sách 10 sản phẩm bán chạy nhất từ mô hình ProductModel.
     */
    public function getBestSellerProducts()
    {
        try {
            $bestSeller = $this->productModel->getTop10Seller();

            $result = [
                'status' => 200,
                'message' => 'Get Success',
                'data' => $bestSeller,
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 404,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức getChart lấy dữ liệu biểu đồ từ mô hình OrderModel.
     */
    public function getChart()
    {
        try {
            $filteredData = $this->orderModel->getChartData();

            $result = [
                'status' => 200,
                'message' => 'Get Success',
                'data' => $filteredData,
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 404,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức getChartFiltered nhận dữ liệu ngày bắt đầu và ngày kết thúc từ form thông qua phương thức POST.
     * Lấy dữ liệu biểu đồ theo khoảng thời gian từ mô hình OrderModel.
     * Trả về dữ liệu JSON chứa dữ liệu biểu đồ với mã trạng thái 200.
     * Nếu có lỗi, trả về mã lỗi 404 và thông báo lỗi.
     */
    public function getChartFiltered()
    {
        try {
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];

            $filteredData = $this->orderModel->getChartDataFiltered($startDate, $endDate);

            $result = [
                'status' => 200,
                'message' => 'Get Success',
                'data' => $filteredData,
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 404,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }
}
