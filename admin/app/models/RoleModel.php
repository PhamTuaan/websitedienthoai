<?php
class RoleModel extends BaseModel
{
    const TableName = 'roles';

    public function getRoles()
    {
        $sql = "SELECT r.* FROM roles as r WHERE r.status = 1 AND r.role_id != 1 ORDER BY r.status DESC";
        $result = $this->querySql($sql);
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public function getRolesNoAdmin()
    {
        $sql = "SELECT r.* FROM roles as r WHERE r.status = 1 AND r.role_id != 1 ORDER BY r.status DESC";
        $result = $this->querySql($sql);
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public function getRole($id)
    {
        // Sửa cú pháp string
        $sql = "SELECT * FROM roles WHERE role_id = {$id} AND status = 1";
        $query = $this->querySql($sql);
        return mysqli_fetch_assoc($query);
    }

    public function getPermissionsByRoleId($id)
    {
        $sql = "SELECT * FROM role_permissions WHERE role_id = {$id}";
        $query = $this->querySql($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;  
        }
        return $data;
    }

    public function createRole($data)
    {
        return $this->create(self::TableName, $data);
    }

    public function deleteRolePermission($role_id)
    {
        $sql = "DELETE FROM role_permissions WHERE role_id = {$role_id}";
        return $this->querySql($sql);
    }

    public function updateRole($id, $data)
    {
        return $this->update(self::TableName, 'role_id', $id, $data);
    }

    public function deleteRole($id)
    {
        $sql = "UPDATE " . self::TableName . " SET status = 0 WHERE role_id = {$id}";
        return $this->querySql($sql);
    }
}
