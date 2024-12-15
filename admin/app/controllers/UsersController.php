<?php
class UsersController extends BaseController
{

    private $userModel; // Thuộc tính lưu đối tượng UserModel
    private $roleModel; // Thuộc tính lưu đối tượng RoleModel

    /**
     * Phương thức khởi tạo (constructor)
     * Khởi tạo controller và tạo đối tượng UserModel, RoleModel để sử dụng trong các phương thức khác.
     */
    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
        $this->roleModel = $this->model('RoleModel');
    }

    /**
     * Phương thức index
     * Hiển thị trang danh sách người dùng.
     */
    public function index()
    {
        // Gọi view để hiển thị trang danh sách người dùng
        $this->view(
            'app',
            [
                'page' => 'users/index',
                'title' => 'Khách hàng',
            ]
        );
    }

     /**
     * Phương thức all
     * Trả về danh sách tất cả người dùng dưới dạng JSON.
     */
    public function all()
    {
        $users = $this->userModel->getUsers(); // Lấy danh sách người dùng

        $result = [
            'status' => 200,
            'data' => $users // Trả về danh sách người dùng
        ];

        header('Content-Type: application/json'); // Định dạng trả về là JSON
        echo json_encode($result); // Trả về dữ liệu dưới dạng JSON
    }

    /**
     * Phương thức disable
     * Vô hiệu hóa người dùng (đặt trạng thái của người dùng thành 0).
     */
    public function disable($id)
    {
        try {
            $user = $this->userModel->getUser($id); // Lấy thông tin người dùng theo ID
            if (!$user) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy nhân viên' // Nếu không tìm thấy người dùng
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            // Cập nhật trạng thái người dùng thành 0 (vô hiệu hóa)
            if ($this->userModel->updateStatus($id, 0)) {
                $result = [
                    'status' => 200,
                    'message' => 'Vô hiệu hóa nhân viên thành công'
                ];
            } else {
                $result = [
                    'status' => 500,
                    'message' => 'Có lỗi khi vô hiệu hóa nhân viên'
                ];
            }

            header('Content-Type: application/json');
            echo json_encode($result);
            
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => $th->getMessage() // Xử lý lỗi
            ];
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức restore
     * Kích hoạt lại người dùng (đặt trạng thái của người dùng thành 1).
     */
    public function restore($id)
    {
        try {
            $user = $this->userModel->getUser($id); // Lấy thông tin người dùng theo ID
            if (!$user) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy nhân viên'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            // Cập nhật trạng thái người dùng thành 1 (kích hoạt lại)
            if ($this->userModel->updateStatus($id, 1)) {
                $result = [
                    'status' => 200, 
                    'message' => 'Kích hoạt nhân viên thành công'
                ];
            } else {
                $result = [
                    'status' => 500,
                    'message' => 'Có lỗi khi kích hoạt nhân viên'
                ];
            }

            header('Content-Type: application/json');
            echo json_encode($result);
            
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => $th->getMessage()
            ];
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức create
     * Hiển thị trang tạo người dùng mới.
     */
    public function create()
    {
        $roles = $this->roleModel->getRolesNoAdmin(); // Lấy danh sách các vai trò (trừ admin)
        // Gọi view để hiển thị trang tạo người dùng mới
        $this->view(
            'app',
            [
                'page' => 'users/create',
                'title' => 'Khach hang',
                'roles' => $roles
            ]
        );
    }

     /**
     * Phương thức store
     * Lưu thông tin người dùng mới vào cơ sở dữ liệu.
     */
    public function store()
    {
        try {
            // Lấy thông tin từ form POST
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
            $address = isset($_POST['address']) ? $_POST['address'] : '';
            $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
            $role = isset($_POST['role']) ? $_POST['role'] : '';

            // Kiểm tra xem thông tin bắt buộc đã được cung cấp chưa
            if (!$fullname || !$email || !$password || !isset($gender)) {
                $result = [
                    'status' => 500,
                    'message' => 'Thiếu thông tin bắt buộc',
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            // kiếm email đã tồn tại hay chưa
            $user = $this->userModel->findEmail($email);

            // Nếu email đã tồn tại
            if($user){
                // Trả về thông báo tồn tại
                $result = [
                    'status' => 500,
                    'message' => 'Email đã tồn tại',
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            } 

           // Tạo dữ liệu người dùng mới
            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT), // Mã hóa mật khẩu
                'gender' => $gender,
                'address' => $address,
                'phone' => $phone,
                'role_id' => $role, // Vai trò của người dùng
            ];

            // Lưu người dùng vào cơ sở dữ liệu
            $this->userModel->createUser($data);

            $result = [
                'status' => 200,
                'message' => 'Nhân viên đã được tạo thành công'
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức edit
     * Hiển thị trang chỉnh sửa thông tin người dùng.
     */
    public function edit($id)
    {
        $roles = $this->roleModel->getRolesNoAdmin(); // Lấy danh sách các vai trò
        $user = $this->userModel->getUser($id); // Lấy thông tin người dùng theo ID

        if (!$user) {
            $_SESSION['success'] = 'Không tìm thấy nhân viên';
            header('Location: /phone-ecommerce-chat/admin/users');
        }

         // Gọi view để hiển thị trang chỉnh sửa
        $this->view(
            'app',
            [
                'page' => 'users/edit',
                'user' => $user,
                'roles' => $roles
            ]
        );
    }

     /**
     * Phương thức update
     * Cập nhật thông tin người dùng.
     */
    public function update($id)
    {
        try {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
            $address = isset($_POST['address']) ? $_POST['address'] : '';
            $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
            $role = $_POST['role'];
 
            // Kiểm tra thông tin bắt buộc
            if (!$fullname || !$email) {
                $_SESSION['errors']['fullname'] = 'Thiếu thông tin bắt buộc';
                header('Location: /phone-ecommerce-chat/admin/users/edit/' . $id);
                exit();
            }

           

            $oldFileName = null;
            $user = $this->userModel->getUser($id);
         
            // Cập nhật dữ liệu người dùng
            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password == "" ? $user['password'] : password_hash($password, PASSWORD_DEFAULT),
                'gender' => $gender,
                'address' => $address,
                'phone' => $phone,
                'role_id' => $role,
            ];
            // Cập nhật thông tin người dùng
            $this->userModel->updateUser($id, $data);

            $result = [
                'status' => 200,
                'message' => 'Cập nhật tài khoản thành công'
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 200,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức updateProfile
     * Cập nhật thông tin người dùng trong hồ sơ của họ.
     */
    public function updateProfile($id)
    {
        try {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
            $address = isset($_POST['address']) ? $_POST['address'] : '';
            $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

            if (!$fullname || !$email) {
                $result = [
                    'status' => 200,
                    'message' => 'Thiếu thông tin bắt buộc',
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;;
            }

            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password == "" ? $user['password'] : password_hash($password, PASSWORD_DEFAULT),
                'gender' => $gender,
                'address' => $address,
                'phone' => $phone,
            ];

            $this->userModel->updateUser($id, $data);

            $result = [
                'status' => 200,
                'message' => 'Cập nhật tài khoản thành công'
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 200,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức destroy
     * Xóa người dùng khỏi hệ thống.
     */
    public function destroy($id)
    {
        try {
            $user = $this->userModel->getUser($id); // Lấy thông tin người dùng theo ID

            if (!$user) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy nhân viên'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            $this->userModel->deleteUser($id);

            $result = [
                'status' => 204,
                'message' => "Xóa nhân viên thành công"
            ];

            header('Location: /phone-ecommerce-chat/admin/users'); // Chuyển hướng về danh sách người dùng

            // header('Content-Type: application/json');
            // echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => $th->getMessage(),
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }
}
