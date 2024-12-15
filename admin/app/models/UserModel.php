<?php
class UserModel extends BaseModel
{
    const TableName = 'users';

    public function getUserId()
    {
        return isset($_SESSION['auth_admin']['user_id']) ? $_SESSION['auth_admin']['user_id'] : 1;
    }

    public function getUsers()
    {
        $sql = "SELECT users.*, roles.role_name 
                FROM users 
                INNER JOIN roles ON users.role_id = roles.role_id 
                WHERE users.user_id != 18";
        $result = $this->querySql($sql);

        if ($result) {
            $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $users;
        }

        return [];
    }
    
    public function checkUserCredentials($email, $password) {
        $sql = "SELECT u.*, r.role_name, r.role_id
                FROM users u 
                JOIN roles r ON u.role_id = r.role_id
                WHERE u.email = ? AND u.status = 1 
                LIMIT 1";
                
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        
        return null;
    }

    public function getUser($id)
    {
        $sql = "SELECT u.* FROM users as u WHERE u.user_id = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function findEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createUser($data)
    {
        return $this->create(self::TableName, $data);
    }

    public function updateUser($id, $data)
    {
        return $this->update(self::TableName, 'user_id', $id, $data);
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function updateStatus($id, $newStatus)
    {
        $sql = "UPDATE users SET status = ? WHERE user_id = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("ii", $newStatus, $id);
        return $stmt->execute();
    }

    public function getTotalUser()
    {
        $sql = "SELECT COUNT(*) AS totalUser FROM users ORDER BY created_at DESC";
        $result = $this->querySql($sql);
        return 10;
    }
}