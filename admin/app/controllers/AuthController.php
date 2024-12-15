<?php

class AuthController extends BaseController
{

    private $userModel;
    private $permissionModel;
    private $customerModel;
    private $cartModel;
    
    /**
     * Lớp AuthController này xử lý các thao tác cơ bản về xác thực người dùng như đăng nhập,
     *  đăng ký, quên mật khẩu, thay đổi mật khẩu và đăng xuất.
     */

    /**
     * Khởi tạo các model cần thiết cho controller.
     */
    public function __construct()
    {
        // Khởi tạo session ngay từ đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userModel = $this->model('UserModel');
        $this->customerModel = $this->model('CustomerModel');
        $this->cartModel = $this->model('CartModel');
        $this->permissionModel = $this->model('PermissionModel');
        
        // Khởi tạo biến login_attempts nếu chưa tồn tại
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
    }

    /**
     * Phương thức mặc định.
     */
    public function index()
    {
        if ($this->isAuthenticated()) {
            header('Location: /phone-ecommerce-chat/admin/home');
            exit();
        }
        header('Location: /phone-ecommerce-chat/admin/auth/login');
    }

    /**
     * Hiển thị trang đăng nhập.
     */
    private function isAuthenticated()
    {
        return isset($_SESSION['auth_admin']) && 
               isset($_SESSION['authenticated_admin']) && 
               $_SESSION['authenticated_admin'] === true;
    }

    private function handleLoginAttempts()
    {
        // Kiểm tra và xử lý login attempts
        if ($_SESSION['login_attempts'] > 5) {
            if (!isset($_SESSION['login_blocked_until'])) {
                $_SESSION['login_blocked_until'] = time() + 300; // 5 phút
            }

            if ($_SESSION['login_blocked_until'] > time()) {
                header('HTTP/1.1 429 Too Many Requests');
                echo json_encode([
                    'status' => 429,
                    'message' => 'Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau 5 phút.'
                ]);
                exit();
            } else {
                $_SESSION['login_attempts'] = 0;
                unset($_SESSION['login_blocked_until']);
            }
        }
    }

    public function login()
    {
        if ($this->isAuthenticated()) {
            header('Location: /phone-ecommerce-chat/admin/home');
            exit();
        }


        $this->view('app', [
            'page' => 'auth/login',
            'title' => 'Đăng nhập'
        ]);
    }
    

    /**
     * Hiển thị trang đăng ký.
     */
    public function register()
    {
        if ($this->isAuthenticated()) {
            header('Location: /phone-ecommerce-chat/admin/home');
            exit();
        }

        $this->view('app', [
            'page' => 'auth/register',
            'title' => 'Đăng ký'
        ]);
    }

    /**
     * Xử lý quên mật khẩu, chuyển hướng nếu người dùng chưa đăng nhập.
     */
    public function forgotPassword()
    {
        if (!$this->isAuthenticated()) {
            header('Location: /phone-ecommerce-chat/admin/auth/login');
            return;
        }

        $this->view('app', [
            'page' => 'auth/forgot-password',
            'title' => 'Lấy lại mật khẩu'
        ]);
    }


    /**
     * Xử lý đăng nhập, kiểm tra thông tin người dùng và trả về kết quả qua JSON.
     */
    public function signIn()
    {
        try {
            // Kiểm tra nếu tài khoản đang bị khóa
            if (isset($_SESSION['login_blocked_until']) && $_SESSION['login_blocked_until'] > time()) {
                throw new Exception('Tài khoản tạm thời bị khóa. Vui lòng thử lại sau.');
            }

            // Kiểm tra dữ liệu POST
            if (!isset($_POST['email']) || !isset($_POST['password'])) {
                $_SESSION['login_attempts']++;
                throw new Exception('Email và mật khẩu là bắt buộc');
            }

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            if (!$email || !$password) {
                $_SESSION['login_attempts']++;
                throw new Exception('Email và mật khẩu là bắt buộc');
            }

            $user = $this->userModel->checkUserCredentials($email, $password);

            if (!$user) {
                $_SESSION['login_attempts']++;
                throw new Exception('Email hoặc mật khẩu không chính xác');
            }

            if ($user['status'] != 1 || $user['deleted_by'] !== null) {
                throw new Exception('Tài khoản đã bị vô hiệu hóa');
            }

            // Reset login attempts sau khi đăng nhập thành công
            $_SESSION['login_attempts'] = 0;
            unset($_SESSION['login_blocked_until']);
            
            $_SESSION['auth_admin'] = $user;
            $_SESSION['authenticated_admin'] = true;

            $permissions = $this->permissionModel->getPermissionByUser($user['user_id']);
            $_SESSION['permissions'] = array_column($permissions, 'permission_code');

            $result = [
                'status' => 200,
                'message' => 'Đăng nhập thành công'
            ];

        } catch (\Throwable $th) {
            error_log("Login error: " . $th->getMessage());
            $result = [
                'status' => 401,
                'message' => $th->getMessage()
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Xử lý đăng ký, tạo mới khách hàng và giỏ hàng, trả về kết quả qua JSON.
     */
    public function signUp()
    {
        try {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $fullname = filter_var($_POST['customer_name'], FILTER_SANITIZE_STRING);

            if (!$email || !$password || !$fullname) {
                throw new Exception('Vui lòng điền đầy đủ thông tin');
            }

            if ($this->customerModel->findEmail($email)) {
                throw new Exception('Email đã tồn tại');
            }

            $this->customerModel->beginTransaction();

            try {
                $customerData = [
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'customer_name' => $fullname,
                    'status' => 1
                ];

                $this->customerModel->createCustomer($customerData);
                $createdCustomer = $this->customerModel->getLastCustomer();

                $cartData = [
                    'customer_id' => $createdCustomer['customer_id']
                ];

                $this->cartModel->createCart($cartData);
                $this->customerModel->commit();

                $result = [
                    'status' => 200,
                    'message' => 'Tạo tài khoản thành công'
                ];

            } catch (\Throwable $th) {
                $this->customerModel->rollback();
                throw $th;
            }

        } catch (\Throwable $th) {
            error_log("Registration error: " . $th->getMessage());
            $result = [
                'status' => 500,
                'message' => $th->getMessage()
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Xử lý thay đổi mật khẩu cho khách hàng, cập nhật mật khẩu và đăng xuất.
     */
    public function changePassword()
    {
        try {
            if (!$this->isAuthenticated()) {
                throw new Exception('Vui lòng đăng nhập');
            }

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $id = $_SESSION['auth_admin']['user_id'];

            if (!$email || !$password) {
                throw new Exception('Email và mật khẩu mới là bắt buộc');
            }

            $user = $this->userModel->findEmail($email);
            if (!$user) {
                throw new Exception('Email không tồn tại');
            }

            $updateData = [
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if (!$this->userModel->updateUser($id, $updateData)) {
                throw new Exception('Không thể cập nhật mật khẩu');
            }

            // Đăng xuất sau khi đổi mật khẩu
            $this->logout();

            $result = [
                'status' => 200,
                'message' => 'Đổi mật khẩu thành công, vui lòng đăng nhập lại'
            ];

        } catch (\Throwable $th) {
            error_log("Change password error: " . $th->getMessage());
            $result = [
                'status' => 500,
                'message' => $th->getMessage()
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Xử lý đăng xuất, xóa thông tin người dùng khỏi session và chuyển hướng.
     */
    public function logout()
    {
        $auth_sessions = [
            'auth_admin', 
            'authenticated_admin', 
            'permissions', 
            'login_attempts', 
            'login_blocked_until'
        ];
        
        foreach ($auth_sessions as $session) {
            if (isset($_SESSION[$session])) {
                unset($_SESSION[$session]);
            }
        }

        session_destroy();
        session_start();
        
        header('Location: /phone-ecommerce-chat/admin/auth/login');
        exit();
    }
}
