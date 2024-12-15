<?php
class CustomerModel extends BaseModel
{
    const TableName = 'customers';

    public function getCustomers()
    {
        $sql = "SELECT c.* FROM customers as c 
        ORDER BY c.status DESC, c.created_at DESC";
        $result = $this->querySql($sql);

        if ($result) {
            $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $customers;
        }

        return [];
    }

    public function getCustomer($id)
    {
        $sql = "SELECT c.*, c.deleted_by 
                FROM customers as c 
                WHERE c.customer_id = '{$id}'";
        $result = $this->querySql($sql);
        return mysqli_fetch_assoc($result);
    }

    public function findEmail($email)
    {
        $sql = "SELECT * FROM customers WHERE email = '{$email}'";
        $result =  $this->querySql($sql);
        return mysqli_fetch_array($result);
    }

    public function getTotalCustomer()
    {
        $sql = "SELECT COUNT(*) AS totalCustomer FROM customers WHERE status = 1";
        $result = $this->querySql($sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['totalCustomer'];
        }
        return 0;
    }

    public function getLastCustomer()
    {
        $sql = "SELECT u.customer_id FROM customers AS u ORDER BY u.created_at DESC LIMIT 1";
        $result = $this->querySql($sql);
        return mysqli_fetch_assoc($result);
    }

    public function createCustomer($data)
    {
        return $this->create(self::TableName, $data);
    }

    public function updateCustomer($id, $data)
    {
        return $this->update(self::TableName, 'customer_id', $id, $data);
    }

    public function deleteCustomer($id) 
    {
        $user_id = isset($_SESSION['auth_admin']['user_id']) ? $_SESSION['auth_admin']['user_id'] : 1;
        
        $customer = $this->getCustomer($id);
        
        $deleted_by = $customer['status'] ? $user_id : 'NULL';
        
        $sql = "UPDATE " . self::TableName . " 
                SET status = NOT status, 
                    deleted_by = {$deleted_by} 
                WHERE customer_id = '{$id}'";
                
        return $this->querySql($sql);
    }
}
