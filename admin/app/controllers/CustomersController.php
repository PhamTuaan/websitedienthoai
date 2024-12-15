<?php
/**
 * Controller này quản lý các thao tác liên quan đến khách hàng
 * như hiển thị danh sách khách hàng, tạo mới, chỉnh sửa, cập nhật và xóa khách hàng.
 */
class CustomersController extends BaseController
{

    private $customerModel;

    public function __construct()
    {
        $this->customerModel = $this->model('CustomerModel');
    }

    /**
     * Phương thức index render view app với các tham số truyền vào
     * để hiển thị trang danh sách khách hàng.
     */
    public function index()
    {
        $this->view(
            'app',
            [
                'page' => 'customers/index',
                'title' => 'Khách hàng',
            ]
        );
    }

    /**
     * Phương thức all lấy danh sách khách hàng từ mô hình CustomerModel.
     * Trả về dữ liệu JSON chứa danh sách khách hàng với mã trạng thái 200.
     */
    public function all()
    {
        $customers = $this->customerModel->getCustomers();

        $result = [
            'status' => 200,
            'data' => $customers
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Phương thức create render view app với các tham số truyền vào để hiển thị trang tạo khách hàng mới.
     */
    public function create()
    {
        $this->view(
            'app',
            [
                'page' => 'customers/create',
                'title' => 'Thống kê'
            ]
        );
    }

    /**
     * Phương thức store nhận dữ liệu từ form thông qua phương thức POST và tạo khách hàng mới.
     *Kiểm tra các thông tin bắt buộc như tên khách hàng, email, mật khẩu, ngày sinh, địa chỉ và số điện thoại.
     *Nếu thiếu thông tin bắt buộc, trả về mã lỗi 404 và thông báo lỗi.
     *Nếu đủ thông tin, mã hóa mật khẩu và gọi phương thức createCustomer của mô hình CustomerModel để lưu khách hàng mới.
     *Trả về dữ liệu JSON với mã trạng thái 200 nếu thành công.
     *Nếu có lỗi, trả về mã lỗi 200 và thông báo lỗi.
     */
    public function store()
    {
        try {
            $customerName = $_POST['customer_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
            $address = isset($_POST['address']) ? $_POST['address'] : '';
            $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

            if (!$customerName || !$email || !$password || !$birthday || !$address || !$phone) {
                $result = [
                    'status' => 404,
                    'message' => 'Thiếu thông tin bắt buộc'
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            $data = [
                'customer_name' => $customerName,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'birthday' => $birthday,
                'address' => $address,
                'phone' => $phone,
            ];

            $this->customerModel->createCustomer($data);

            $result = [
                'status' => 200,
                'message' => 'Khách hàng đã được tạo thành công'
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
     * Phương thức edit lấy thông tin khách hàng theo ID từ mô hình CustomerModel.
     * Nếu không tìm thấy khách hàng, đặt thông báo vào session và điều hướng về trang danh sách khách hàng.
     * Nếu tìm thấy khách hàng, render view app với các tham số truyền vào để hiển thị trang chỉnh sửa khách hàng.
     */
    public function edit($id)
    {
        $customer = $this->customerModel->getCustomer($id);

        if (!$customer) {
            $_SESSION['success'] = 'Không tìm thấy khách hàng';
            header('Location: /phone-ecommerce-chat/admin/customers');
        }

        $this->view(
            'app',
            [
                'page' => 'customers/edit',
                'customer' => $customer,
            ]
        );
    }

    /**
     * Phương thức update nhận dữ liệu từ form thông qua phương thức POST và cập nhật thông tin khách hàng.
     * Kiểm tra các thông tin bắt buộc như tên khách hàng, email, ngày sinh, địa chỉ và số điện thoại.
     * Nếu thiếu thông tin bắt buộc, trả về mã lỗi 404 và thông báo lỗi.
     * Nếu đủ thông tin, gọi phương thức updateCustomer của mô hình CustomerModel để cập nhật thông tin khách hàng.
     * Trả về dữ liệu JSON với mã trạng thái 200 nếu thành công.
     * Nếu có lỗi, trả về mã lỗi 200 và thông báo lỗi.
     */
    public function update($id)
    {
        try {
            $existingCustomer = $this->customerModel->getCustomer($id);
            if (!$existingCustomer) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy khách hàng'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }
    
            if ($existingCustomer['deleted_by'] !== null) {
                $result = [
                    'status' => 403,
                    'message' => 'Không thể cập nhật khách hàng đang bị vô hiệu hoá'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }
    
            $customerName = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $birthday = isset($_POST['birthday']) ? trim($_POST['birthday']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    
            if (!$customerName || !$email || !$birthday || !$address || !$phone) {
                $result = [
                    'status' => 404,
                    'message' => 'Thiếu thông tin bắt buộc'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }
    
            $data = [
                'customer_name' => $customerName,
                'email' => $email,
                'birthday' => $birthday,
                'address' => $address,
                'phone' => $phone,
            ];
    
            $this->customerModel->updateCustomer($id, $data);
    
            $result = [
                'status' => 200,
                'message' => 'Khách hàng đã được cập nhật thành công'
            ];
    
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
     * Phương thức destroy xóa khách hàng theo ID từ mô hình CustomerModel.
     * Kiểm tra xem khách hàng có tồn tại hay không.
     * Nếu không tìm thấy khách hàng, trả về mã lỗi 404 và thông báo lỗi.
     * Nếu tìm thấy khách hàng, gọi phương thức deleteCustomer của mô hình CustomerModel để xóa khách hàng.
     * Sau khi xóa, điều hướng về trang danh sách khách hàng.
     * Nếu có lỗi, trả về mã lỗi 500 và thông báo lỗi.
     */
    public function destroy($id)
    {
        try {
            $customer = $this->customerModel->getCustomer($id);

            var_dump($customer);

            if (!$customer) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy khách hàng'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            $this->customerModel->deleteCustomer($id);

            $result = [
                'status' => 204,
                'message' => "Xóa khách hàng thành công"
            ];

            header('Location: /phone-ecommerce-chat/admin/customers/index');

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
