<?php
class CartController extends BaseController
{
    private $cartModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->cartModel = $this->model('CartModel');
    }

    public function index()
    {
        if (!isset($_SESSION['auth'])) {
            $_SESSION['redirect_after_login'] = '/phone-ecommerce-chat/customer/cart';
            header('Location: /phone-ecommerce-chat/customer/auth/login');
            exit(); 
        }

        $this->view(
            'app',
            [
                'page' => 'cart/index',
                'title' => 'Shop',
            ]
        );
    }

    public function getAll()
    {
        try {
            $cus_id = $_SESSION['auth']['customer_id'];
            $cart = $this->cartModel->getAllCart($cus_id);

            if (!$cart) {
                $result = [
                    'status' => 204,
                    'message' => "Lỗi fetch sản phẩm!"
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            $result = [
                'status' => 200,
                'message' => "success",
                'data' => $cart,
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
            return;
        } catch (\Throwable $th) {
            $result = [
                'status' => 200,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function store()
    {
        try {
            $customer_id = $_SESSION['auth']['customer_id'];
            $product_id = $_POST['product_id']; 
            $quantity = $_POST['quantity'];

            $cart = $this->cartModel->addToCart($customer_id, $product_id, $quantity);

            $result = [
                'status' => 200,
                'message' => "Thêm vào giỏ hàng thành công!"
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

    public function update()
    {
        try {
            $customer_id = $_SESSION['auth']['customer_id'];
            $cartDetails = $_POST['cartDetails'];

            foreach($cartDetails as $item) {
                $this->cartModel->updateCartQuantity(
                    $customer_id,
                    $item['product_id'],
                    $item['quantity']
                );
            }

            $result = [
                'status' => 200,
                'message' => "Đã cập nhật giỏ hàng thành công!"
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
            
        } catch (\Throwable $th) {
            $result = [
                'status' => 404,
                'message' => $th->getMessage()
            ];
            
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function destroy($id)
    {
        try {
            $customer_id = $_SESSION['auth']['customer_id'];
            $product_id = $id;

            $this->cartModel->removeFromCart($customer_id, $product_id);
            
            $result = [
                'status' => 204,
                'message' => "Đã xóa sản phẩm khỏi giỏ hàng!"
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
