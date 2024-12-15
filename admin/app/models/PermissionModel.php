<?php
class PermissionModel extends BaseModel
{
    // Định nghĩa tên bảng chứa quyền và bảng liên kết giữa quyền và vai trò
    const TableName = 'permissions'; 
    const PermissionRoleTable = 'role_permissions';

    // Phương thức này dùng để tạo mới một quyền trong bảng permissions.
    public function createPermission($data)
    {
        return $this->create(self::TableName, $data); // Gọi phương thức create từ lớp cha để thêm quyền vào bảng 'permissions'
    }

    /**
     * Lấy tất cả các quyền có trạng thái 'đang hoạt động' (status = 1).
     * @return array Mảng chứa danh sách quyền đang hoạt động
     */
    public function getPermissions()
    {
        $sql = "SELECT p.* FROM permissions as p Where p.status=1  ORDER BY p.status DESC"; // Truy vấn các quyền có status = 1
        $result = $this->querySql($sql);
        if ($result) {
            $permissions = mysqli_fetch_all($result, MYSQLI_ASSOC); // Lấy kết quả truy vấn dưới dạng mảng kết hợp
            return $permissions;
        }
        return [];  // Trả về mảng rỗng nếu không có quyền nào
    }

    /**
     * Liên kết một quyền với một vai trò.
     *  $roleId ID của vai trò
     *  $permissionId ID của quyền
     *  Kết quả của việc thêm liên kết vào bảng 'role_permissions'
     */
    public function associatePermissionWithRole($roleId, $permissionId)
    {
        $sql = "
            INSERT INTO role_permissions(role_id, permission_id) 
            VALUE({$roleId}, {$permissionId})
        "; // Thực hiện thêm một bản ghi vào bảng role_permissions để liên kết quyền và vai trò
        return $this->querySql($sql);  // Trả về kết quả của câu lệnh SQL
    }

    /**
     * Lấy tất cả quyền liên kết với một vai trò theo ID.
     * @param int $id ID của vai trò
     * @return array Mảng chứa các quyền đã được liên kết với vai trò
     */
    public function getPermissionsByRoleId($id)
    {
        $sql = "SELECT * FROM role_permissions  WHERE role_id = {$id}"; // Truy vấn các quyền liên kết với vai trò có ID $id
        $result = $this->querySql($sql); // Thực hiện truy vấn
        if ($result) {
            $permissions = mysqli_fetch_all($result, MYSQLI_ASSOC);  // Lấy kết quả truy vấn dưới dạng mảng kết hợp
            return $permissions;
        }
        return []; // Trả về mảng rỗng nếu không có quyền nào
    }

    /**
     * Lấy quyền của người dùng theo ID người dùng.
     * 
     * @param int $user_id ID của người dùng
     * @return array Mảng chứa các quyền của người dùng, thông qua vai trò của họ
     */
    public function getPermissionByUser($user_id)
    {
      $sql ="SELECT p.permission_code
            FROM users u
            JOIN roles r ON u.role_id = r.role_id
            JOIN role_permissions rp ON r.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.permission_id
            WHERE u.user_id = {$user_id}"; // Truy vấn để lấy quyền của người dùng thông qua vai trò của họ
        $result = $this->querySql($sql);
        if ($result) {
            $permissions = mysqli_fetch_all($result, MYSQLI_ASSOC); // Lấy kết quả truy vấn dưới dạng mảng kết hợp
            return $permissions;
        }
        return [];  // Trả về mảng rỗng nếu không có quyền nào
    }
}
