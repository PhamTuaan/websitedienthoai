<?php
class AuthController extends BaseController
{

    private $customerModel;
    private $cartModel;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->customerModel = $this->model('CustomerModel');
        $this->cartModel = $this->model('CartModel');
    }

    public function login()
    {
        $this->view('app', [
            'page' => 'auth/login',
            'title' => 'Đăng nhập',
        ]);
    }

    public function register()
    {
        $this->view('app', [
            'page' => 'auth/register',
            'title' => 'Đăng ký',
        ]);
    }

    public function forgotpassword()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /phone-ecommerce-chat/customer/auth/login');
            return;
        }

        $this->view('app', [
            'page' => 'auth/forgot-password',
            'title' => 'Lấy lại mật khẩu',
        ]);
    }

    /**
     * Login method.
     */
    public function signIn()
    {
        try {
            $email = $_POST['email'];
            $pass = $_POST['password'];

            $result = $this->customerModel->findEmail($email);

            if ($result) {
                /**
                 * Còn đây là lúc đăng nhập 
                 * Sẽ dùng một hàm có sẵn của PHP để so sánh mật khẩu được nhập với mật khẩu đã hash trong DB
                 * password_verify($pass, $result['password'])
                 * Nếu nó trùng nhau thì cho đăng nhập thôi
                 
                 */
                if (password_verify($pass, $result['password'])) {
                    // if ($pass == $result['password']) {
                    $_SESSION['auth'] = $result;
                    $_SESSION['authenticated'] = true;

                    //  **************** GLOBAL CART COUNT VARIABLE***************
                    // $cart = $this->cartModel->getCartByCustomerId($_SESSION['auth']['customer_id']);
                    // $cartCount = $this->cartDetailModel->countCart($_SESSION['auth']['customer_id'], $cart['cart_id']);
                    // $_SESSION['cartCount'] = $cartCount;

                    $result = [
                        'status' => 200,
                        'message' => "Đăng nhập thành công!",
                        'data' => $result
                    ];

                    header('Content-Type: application/json');
                    echo json_encode($result);
                } else {
                    $result = [
                        'status' => 404,
                        'message' => "Mật khẩu không chính xác!"
                    ];

                    header('Content-Type: application/json');
                    echo json_encode($result);
                }
            } else {
                $result = [
                    'status' => 204,
                    'message' => "Email không tồn tại!"
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
            }
        } catch (\Throwable $th) {
            $result = [
                'status' => 204,
                'message' => $th->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function signUp()
    {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $fullname = $_POST['customer_name'];

            $customer = $this->customerModel->findEmail($email);

            if ($customer) {
                $result = [
                    'status' => 204,
                    'message' => "Email đã tồn tại!"
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            if (!isset($password)) {
                $result = [
                    'status' => 404,
                    'message' => "Mật khẩu hoặc email không hợp lệ."
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            } 


            /**
             * Mã hóa mật khẩu dùng hash passord 
             * Cơ bản thì hash nó là một dạng mã hóa 1 chiều từ 1 chuỗi ký tự VD:12345 nó sẽ trộn lẫn với nhiều thứ
             * + với cái password => thành 1 chuỗi ký tự được mã hóa
             * Do nó là mã hóa 1 chiều cho nên VD nếu bị mất dữ liệu vào tay hacker chăng hạn thì người ta sẽ không thể đảo ngược
             * cái mật khẩu được mã hóa thành mật khẩu ban đầu được cho nên đây là một cách bảo mật hay thấy ở mấy ứng dụng web
             * password_hash là hàm có sẵn của php nó hỗ trợ việc hash password nhanh với dễ dàng hơn thôi chứ ko có gì. 
             * 
             * Chỉ cần hash cái password thôi còn mấy thằng khác không cần
             * Đây là lúc đăng ký tài khoản thì cần hash password của người dùng để lưu vô DB
             */
            $data = [
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'customer_name' => $fullname,
                'status' => 1 
            ];

            $this->customerModel->createCustomer($data);
            $createdCustomer = $this->customerModel->getLastCustomer();

            try {
                $cartData = [
                    'customer_id' => $createdCustomer['customer_id']
                ];
                $this->cartModel->createCart($cartData);
            } catch (Exception $e) {
                error_log("Failed to create cart: " . $e->getMessage());
            }
    

            $result = [
                'status' => 200,
                'message' => "Tạo tài khoản thành công!"
            ];
    
            header('Content-Type: application/json');
            echo json_encode($result);

        } catch (Exception $e) {
            $result = [
                'status' => 404,
                'message' => "Tạo tài khoản thất bại!, " . $e->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function changePassword()
    {
        try {
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $id = $_SESSION['auth']['customer_id'];

            $result = $this->customerModel->findEmail($email);

            if ($result) {
                $data = [
                    'password' => password_hash($pass, PASSWORD_DEFAULT),
                ];


                $this->customerModel->updateCustomer($id, $data);

                $_SESSION['auth'] = null;
                $_SESSION['authenticated'] = false;


                $result = [
                    'status' => 200,
                    'message' => "Đổi mật khẩu thành công vui lòng đăng nhập lại!"
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                $result = [
                    'status' => 204,
                    'message' => "Email không tồn tại!"
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
            }
        } catch (\Throwable $th) {
            $result = [
                'status' => 204,
                'message' => $th->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function logout()
    {
        if ($_SESSION['auth']) {
            unset($_SESSION['auth']);
            unset($_SESSION['authenticated']);

            $result = [
                'status' => 200,
                'message' => "Đã đăng xuất!"
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }
}
