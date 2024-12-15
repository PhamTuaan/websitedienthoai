<?php

class CheckoutController extends BaseController
{
    private $cartModel; 
    private $orderModel;
    private $orderDetailModel;
    private $productModel;
    private $promotionModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->cartModel = $this->model('CartModel');
        $this->orderModel = $this->model('OrderModel');
        $this->productModel = $this->model('ProductModel');
        $this->orderDetailModel = $this->model('OrderDetailModel');
        $this->promotionModel = $this->model('PromotionModel');
    }

    public function index()
    {
        if (!isset($_SESSION['auth'])) {
            $_SESSION['redirect_after_login'] = '/phone-ecommerce-chat/customer/checkout';
            header('Location: /phone-ecommerce-chat/customer/auth/login');
            exit();
        }

        $this->view('app', [
            'page' => 'checkout/index',
            'title' => 'Checkout',
        ]);
    }

    public function processCheckout()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /phone-ecommerce-chat/customer/auth/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
            if (is_array($_POST['selected_items'])) {
                $list_cartDetailId = $_POST['selected_items'];
            } else {
                $list_cartDetailId = explode(',', $_POST['selected_items']);
            }

            $listData = [];

            foreach ($list_cartDetailId as $item) {
                $existingCartDetail = $this->cartModel->getCartDetail($item);
                $listData[] = $existingCartDetail;
            }

            $result = [
                'status' => 200,
                'message' => "Tiến hành thanh toán!",
                'data' => $listData
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function getPromotionByCode(){
        $promotion_code = $_POST['promotion_code'];
        $promotion = $this->promotionModel->getPromotionByCode($promotion_code);

        $result = [
            'status' => 200,
            'message' => "Lấy mã giảm giá thành công!",
            'data' => $promotion
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function store()
    {
        try {
            if (!isset($_POST['name_receiver']) || !isset($_POST['phone_receiver']) || 
                !isset($_POST['address_receiver']) || !isset($_POST['total_price']) || 
                !isset($_POST['listProductDetail']) || !is_array($_POST['listProductDetail'])) {
                throw new Exception("Dữ liệu không hợp lệ!");
            }
    
            $customerId = $_SESSION['auth']['customer_id'];
            $nameReceiver = $_POST['name_receiver'];
            $phoneReceiver = $_POST['phone_receiver'];
            $addressReceiver = $_POST['address_receiver'];
            $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
            $totalPrice = $_POST['total_price'];
            $listProductDetail = $_POST['listProductDetail'];
    
            error_log("Received order data: " . print_r($_POST, true));
    
            $dataOrder = [
                'customer_id' => $customerId,
                'name_receiver' => $nameReceiver,
                'phone_receiver' => $phoneReceiver,
                'address_receiver' => $addressReceiver,
                'notes' => $notes,
                'total_price' => $totalPrice,
            ];
    
            if(isset($_POST['promotion_id']) && !empty($_POST['promotion_id'])) {
                $promotion_id = $_POST['promotion_id'];
                $promotion = $this->promotionModel->getPromotionById($promotion_id);
                if(!$promotion || $promotion['promotion_used'] == 1) {
                    throw new Exception("Mã giảm giá không hợp lệ");
                }
                $dataOrder['promotion_id'] = $promotion_id;
                $this->promotionModel->updatePromotion($promotion_id);
            }
    
            $this->orderModel->createOrder($dataOrder);
            $order = $this->orderModel->getLastestOrder();
    
            foreach ($listProductDetail as $item) {
                $product = $this->productModel->getById($item['product_id']);
                if($product['quantity'] < $item['quantity']) {
                    throw new Exception("Sản phẩm {$product['product_name']} không đủ số lượng");
                }
                
                if (!isset($item['product_id']) || !isset($item['quantity']) || !isset($item['price'])) {
                    throw new Exception("Dữ liệu sản phẩm không hợp lệ!");
                }
    
                $this->orderDetailModel->createOrderDetail(
                    $order['order_id'],
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                );
    
                $this->cartModel->removeFromCart($customerId, $item['product_id']);
    
                $this->productModel->updateQuantity($item['product_id'], $item['quantity']);
            }
    
            $result = [
                'status' => 200,
                'message' => 'Đặt hàng thành công'
            ];
    
        } catch (\Throwable $th) {
            error_log("Order error: " . $th->getMessage());
            $result = [
                'status' => 400,
                'message' => $th->getMessage()
            ];
        }
    
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }

    public function success()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /phone-ecommerce-chat/customer/auth/login');
            return;
        }

        $this->view(
            'app',
            [
                'page' => 'checkout/success',
                'title' => 'checkout'
            ]
        );
    }
}
