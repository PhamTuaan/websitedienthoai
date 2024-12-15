<?php
class CartModel extends BaseModel
{
    const TableName = 'carts';

    public function getAllCart($customer_id) 
    {
        $sql = "SELECT c.*, p.product_name, p.price, p.image, p.status 
                FROM " . self::TableName . " c
                INNER JOIN products p ON c.product_id = p.product_id 
                WHERE c.customer_id = $customer_id AND p.status = 1";
                
        $result = $this->querySql($sql);
        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return [];
    }

    public function createCart($data)
    {
        $customer_id = $data['customer_id'];
        
        $existing = $this->getAllCart($customer_id);
        if (!empty($existing)) {
            return false; 
        }

        return $this->create(self::TableName, [
            'customer_id' => $customer_id,
            'quantity' => 0  
        ]);
    }

    public function addToCart($customer_id, $product_id, $quantity)
    {
        $sql = "INSERT INTO " . self::TableName . " (customer_id, product_id, quantity) 
                VALUES ($customer_id, $product_id, $quantity)
                ON DUPLICATE KEY UPDATE quantity = quantity + $quantity";
        return $this->querySql($sql);
    }

    public function updateCartQuantity($customer_id, $product_id, $quantity) 
    {
        $sql = "UPDATE " . self::TableName . "
                SET quantity = $quantity 
                WHERE customer_id = $customer_id 
                AND product_id = $product_id";
        return $this->querySql($sql);
    }

    public function removeFromCart($customer_id, $product_id)
    {
        $sql = "DELETE FROM " . self::TableName . "
                WHERE customer_id = $customer_id 
                AND product_id = $product_id";
        return $this->querySql($sql);
    }
}