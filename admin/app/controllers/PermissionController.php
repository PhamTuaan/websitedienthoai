<?php
// $permissionModel: Tham chiếu đến lớp PermissionModel để thao tác dữ liệu quyền.
// $roleModel: Tham chiếu đến lớp RoleModel để thao tác dữ liệu vai trò.
// Phương thức khởi tạo (__construct):
// Khởi tạo hai thuộc tính bằng cách gọi phương thức model(), tạo đối tượng của hai model: PermissionModel và RoleModel.
class PermissionController extends BaseController
{

    private $permissionModel;
    private $roleModel;

    public function __construct()
    {
        $this->permissionModel = $this->model('PermissionModel');
        $this->roleModel = $this->model('RoleModel');
    }

    // Hiển thị giao diện trang chính của quản lý quyền (permissions/index).
    // page: Đường dẫn file view (permissions/index).
    // title: Tiêu đề trang ("Phân quyền").
    public function index()
    {
        $this->view(
            'app',
            [
                'page' => 'permissions/index',
                'title' => 'Phân quyền',
            ]
        );
    }

    // Lấy danh sách tất cả các vai trò (roles) từ cơ sở dữ liệu.
    // Gọi phương thức getRoles() trong RoleModel để lấy dữ liệu.
    // Trả về dữ liệu dưới dạng JSON:
    // status: HTTP status code (200 = thành công).
    // data: Danh sách vai trò.
    public function all()
    {
        $roles = $this->roleModel->getRoles();


        $result = [
            'status' => 200,
            'data' => $roles
        ];
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    // Hiển thị trang giao diện để tạo mới quyền (permissions/create).
    // Gọi phương thức getPermissions() trong PermissionModel để lấy danh sách quyền.
    // Truyền dữ liệu vào view:
    // permissions: Danh sách quyền để hiển thị.
    public function create()
    {
        $permissions = $this->permissionModel->getPermissions();
        $this->view(
            'app',
            [
                'page' => 'permissions/create',
                'title' => 'Tạo quyền',
                'permissions'=> $permissions
            ]
        );
    }

    // Nhận dữ liệu từ yêu cầu POST: permission_name: Tên quyền (vai trò) mới. functions: Mảng các ID quyền được liên kết.
    // Kiểm tra dữ liệu: Nếu có tên quyền (roleName), tiếp tục tạo vai trò mới. Nếu không, trả về lỗi (HTTP status 400).
    // Tạo vai trò mới: Gọi phương thức createRole() trong RoleModel để tạo vai trò mới. Lấy ID vai trò mới tạo ($roleId).
    // Liên kết quyền với vai trò: Nếu có danh sách các quyền (functions), thực hiện vòng lặp:.
    // Gọi associatePermissionWithRole() để liên kết từng quyền với vai trò.
    public function store()
    {
        try {
            $roleName = $_POST['permission_name'];
            $functions = isset($_POST['functions']) ? $_POST['functions'] : [];

            if ($roleName) {
                // Create the role
                $roleId = $this->roleModel->createRole(['role_name' => $roleName]);

                // Associate permissions with the role
                if ($roleId && !empty($functions)) {
                    foreach ($functions as $permissionId) {
                        $this->permissionModel->associatePermissionWithRole($roleId, $permissionId);
                    }
                }

                $result = [
                    'status' => 201,
                    'message' => "Tạo quyền thành công",
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                $result = [
                    'status' => 400,
                    'message' => "Vui lòng cung cấp tên quyền"
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            }
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => "Đã xảy ra lỗi: " . $th->getMessage()
            ];
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    // Hiển thị giao diện chỉnh sửa một vai trò (role) theo ID.
    // Lấy dữ liệu: Vai trò hiện tại: Gọi getRole($id) để lấy thông tin vai trò. Danh sách quyền: Lấy danh sách quyền hiện có.
    // Quyền liên kết: Gọi getPermissionsByRoleId($id) để lấy quyền của vai trò.
    // Nếu không tìm thấy vai trò: Đặt thông báo lỗi và chuyển hướng về trang quản lý quyền. 
    // Truyền dữ liệu vào view: Thông tin vai trò (role). Danh sách quyền (permissions). Quyền đã được liên kết (permission_ids).
    public function edit($id)
    {
        $roles = $this->roleModel->getPermissionsByRoleId($id);
        $role = $this->roleModel->getRole($id);
        $permissions = $this->permissionModel->getPermissions();
        $permission_ids = array_column($roles, 'permission_id');
        if (!$role) {
            $_SESSION['success'] = 'Không tìm thấy quyền';
            header('Location: /phone-ecommerce-chat/admin/permission');
        }

        $this->view(
            'app',
            [
                'page' => 'permissions/edit',
                'title' => 'Thống kê',
                'role' => $role,
                'permissions' => $permissions,
                'permission_ids' => $permission_ids
            ]
        );
    }

    // Cập nhật thông tin một vai trò và danh sách quyền liên kết.
    public function update($id)
    {
        try {
            $roleName = $_POST['permission_name'];
            $functions = isset($_POST['functions']) ? $_POST['functions'] : [];

            if ($roleName) {
                $data = ['role_name' => $roleName];
                $this->roleModel->updateRole($id, $data);

                $this->roleModel->deleteRolePermission($id);
               // Associate permissions with the role
               if ($id && !empty($functions)) {
                    foreach ($functions as $permissionId) {
                        $this->permissionModel->associatePermissionWithRole($id, $permissionId);
                    }
                }

                $result = [
                    'status' => 200,
                    'message' => "Cập nhật quyền thành công"
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                $_SESSION['role_name'] = 'Vui lòng cung cấp tên quyền';
                $result = [
                    'status' => 201,
                    'message' => "Cập nhật quyền thất bại"
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            }
        } catch (\Throwable $th) {
            var_dump($th);
        }
    }

    // Xóa một vai trò (role) theo ID.
    public function destroy($id)
    {
        try {
            $this->roleModel->deleteRole($id);

            $result = [
                'status' => 204,
                'message' => "Xóa quyền thành công"
            ];

            header('Location: /phone-ecommerce-chat/admin/permission');
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => "Xóa quyền thất bại: " . $th->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }
}
