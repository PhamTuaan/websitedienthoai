<?php
// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/PHPMailer/src/Exception.php';
require './vendor/PHPMailer/src/PHPMailer.php';
require './vendor/PHPMailer/src/SMTP.php';

class UserController extends BaseController
{

    private $orderModel;
    private $customerModel;
    private $promotionModel;

    public function __construct()
    {
        $this->orderModel = $this->model('OrderModel');
        $this->customerModel = $this->model('CustomerModel');
        $this->promotionModel = $this->model('PromotionModel');
    }

    public function profile()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /phone-ecommerce-chat/customer/auth/login');
            return;
        }

        $customer = $this->customerModel->getCustomer($_SESSION['auth']['customer_id']);

        $this->view('app', [
            'page' => 'profile/index',
            'title' => 'Thông tin cá nhân',
            'customer' => $customer,
        ]);
    }

    public function orderHistory()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /phone-ecommerce-chat/customer/auth/login');
            return;
        }
        $this->view('app', [
            'page' => 'checkout/history',
            'title' => 'Lịch sử đơn hàng',
        ]);
    }

    public function getOrderHistory() 
    {
        if (!isset($_SESSION['auth'])) {
            $this->json([
                'status' => 401,
                'message' => 'Unauthorized'
            ]);
            return;
        }

        try {
            $customer_id = $_SESSION['auth']['customer_id'];
            
            // 1. Get orders first
            $sql = "SELECT o.*, p.promotion_code, p.value as promotion_value
                    FROM orders o
                    LEFT JOIN promotions p ON o.promotion_id = p.promotion_id
                    WHERE o.customer_id = {$customer_id} AND o.deleted_by IS NULL
                    ORDER BY o.created_at DESC";
            
            $result = $this->orderModel->querySql($sql);
            
            $orders = [];
            while ($order = mysqli_fetch_assoc($result)) {
                // 2. Get order details for each order
                $order_id = $order['order_id'];
                $detailSql = "SELECT od.*, p.product_id, p.product_name, p.price, p.image 
                            FROM order_details od
                            JOIN products p ON od.product_id = p.product_id
                            WHERE od.order_id = {$order_id}";
                
                $detailResult = $this->orderModel->querySql($detailSql);
                
                $orderDetails = [];
                while ($detail = mysqli_fetch_assoc($detailResult)) {
                    $orderDetails[] = [
                        'product' => [
                            'product_id' => $detail['product_id'],
                            'product_name' => $detail['product_name'],
                            'price' => $detail['price'],
                            'image' => $detail['image']
                        ],
                        'quantity' => $detail['quantity']
                    ];
                }
                
                // Add order details to order
                $order['orderDetail'] = $orderDetails;
                
                // Add promotion info if exists
                if ($order['promotion_id']) {
                    $order['promotion'] = [
                        'promotion_code' => $order['promotion_code'],
                        'value' => $order['promotion_value']
                    ];
                }
                
                // Clean up unnecessary fields
                unset($order['promotion_code']);
                unset($order['promotion_value']);
                
                $orders[] = $order;
            }

            $this->json([
                'status' => 200,
                'message' => 'Lấy lịch sử đơn hàng thành công',
                'data' => $orders
            ]);

        } catch (Exception $e) {
            $this->json([
                'status' => 500,
                'message' => 'Lỗi khi lấy lịch sử đơn hàng: ' . $e->getMessage()
            ]);
        }
    }
        

    public function updateProfile()
    {
        try {
            $id = $_SESSION['auth']['customer_id'];
            $existCustomer = $this->customerModel->getCustomer($id);

            if ($existCustomer) {

                $data = [
                    'address' => $_POST['address'],
                    'birthday' => $_POST['birthday'],
                    'customer_name' => $_POST['customer_name'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                ];

                $this->customerModel->updateCustomer($id, $data);

                $_SESSION['auth'] = $this->customerModel->getCustomer($id);

                $result = [
                    'status' => 200,
                    'message' => 'Cập nhật người dùng thành công'
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }
        } catch (\Throwable $th) {
            $result = [
                'status' => 404,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function receiveOrder($id)
    {
        try {
            $existOrder = $this->orderModel->getOrder($id);
            $email = $_SESSION['auth']['email'];
            
            if ($existOrder) {
                $customer = $this->customerModel->getCustomer($existOrder['customer_id']);
                $this->orderModel->updateStatusCompleted($id);

                // Handle send promotion code when pay 20.000.000tr
                //  Thay 20.000.000 thành số khác
                if($customer['customer_points'] > 500) {
                    $promotion_code = rand(000000, 999999);
                    
                    $data_promotion = [
                        'promotion_code' => $promotion_code,
                        'value' => 10,
                        'status' => 1  
                    ];

                    if($customer['customer_points'] > 2000) {
                        $data_promotion['value'] = 20;
                    } elseif($customer['customer_points'] > 1000) {
                        $data_promotion['value'] = 15;
                    }
                    
                    // Save promotion code from database
                    $this->promotionModel->createPromotion($data_promotion);

                    // handle send Email
                    $mail = new PHPMailer();
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    // sử dụng gmail SMTP của google
                    $mail->Username = 'phamtuanvt2015@gmail.com';
                    $mail->Password = 'hggykmzjghvmbefa';
                    $mail->Port = 587;
                    $mail->SMTPSecure = 'tls';

                    // Config header
                    $mail->setFrom('phamtuanvt2015@gmail.com', 'Augentern-shop');
                    $mail->addAddress($email);

                    $promotion_value = $data_promotion['value'];

                    // Config content
                    $mail->isHTML(true);   //Set email format to HTML
                    $mail->Subject = 'Augentern-shop: Voucher';
                    $mail->Body    = "Chúng tôi tặng bạn mã giảm giá {$promotion_value}% tương đương với số điểm tích lũy mã bạn có khi mua thành công đơn hàng: 
                    <b>{$promotion_code}</b>";

                    $mail->send();
                }

                $result = [
                    'status' => 200,
                    'message' => 'Cập nhật order thành công'
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }
        } catch (\Throwable $th) {
            $result = [
                'status' => 404,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function cancleOrder($id)
    {
        try {
            $existOrder = $this->orderModel->getOrder($id);

            if ($existOrder) {
                $this->orderModel->updateStatusCancle($id);

                $result = [
                    'status' => 200,
                    'message' => 'Hủy order thành công'
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }
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
