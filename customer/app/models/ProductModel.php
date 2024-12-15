<?php
class ProductModel extends BaseModel
{
    const TableName = 'products';

    public function getProducts()
    {
        $sql = "SELECT p.*, c.category_name, c.category_id, 
            COALESCE((SELECT AVG(rate) FROM product_reviews WHERE product_id = p.product_id AND status = 1), 0) as rating
            FROM products p
            JOIN categories c ON p.category_id = c.category_id
            WHERE p.status = 1
            ORDER BY p.created_at DESC";
        $result = $this->querySql($sql);
        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $products;
        }
        return [];
    }

    public function getById($id) {
        $sql = "SELECT * FROM products WHERE product_id = '{$id}' AND deleted_by IS NULL";
        $result = $this->querySql($sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    public function getProduct($id)
    {
        $sql = "SELECT p.* ,c.category_name,c.category_id FROM products as p
        JOIN categories as c 
        ON p.category_id = c.category_id
        WHERE p.product_id = {$id} AND p.status = 1
        ORDER BY p.status = 1 DESC, p.created_at DESC";

        $result = $this->querySql($sql);
        return mysqli_fetch_assoc($result);
    }

    public function getTop10NewProduct()
    {
        $sql = "SELECT p.*, c.category_name, c.category_id,
                (SELECT AVG(rate) FROM product_reviews WHERE product_id = p.product_id AND status = 1) as rating
                FROM products p
                JOIN categories c ON p.category_id = c.category_id
                WHERE p.status = 1
                ORDER BY p.created_at DESC
                LIMIT 10";
        $result = $this->querySql($sql);
        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $products;
        }
        return [];
    }

    public function getHotTrend()
    {
        $sql = "SELECT p.*, 
            COALESCE((SELECT AVG(rate) FROM product_reviews WHERE product_id = p.product_id AND status = 1), 0) as rating 
            FROM products p 
            WHERE p.status = 1 
            ORDER BY p.created_at DESC 
            LIMIT 5";
        $result = $this->querySql($sql);
        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $products;
        }
        return [];
    }

    public function getTop10Seller()
    {
        $sql = "SELECT p.*, SUM(od.quantity) AS total_quantity,
                COALESCE((SELECT AVG(rate) FROM product_reviews WHERE product_id = p.product_id AND status = 1), 0) as rating 
                FROM products p
                JOIN order_details od ON od.product_id = p.product_id
                JOIN orders o ON o.order_id = od.order_id
                WHERE p.status = 1 
                GROUP BY p.product_id, p.category_id, p.product_name, p.price, p.quantity, 
                        p.description, p.image, p.status, p.created_at, p.updated_at
                ORDER BY total_quantity DESC
                LIMIT 6";
        $result = $this->querySql($sql);
        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC); 
            return $products;
        }
        return [];
    }


    public function checkProductPurchased($product_id, $customer_id) 
    {
        $sql = "SELECT COUNT(*) as purchase_count 
                FROM orders o 
                JOIN order_details od ON o.order_id = od.order_id 
                WHERE od.product_id = {$product_id} 
                AND o.customer_id = {$customer_id}
                AND o.status != 'đã hủy'";
                
        $result = $this->querySql($sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['purchase_count'] > 0;
        }
        return false;
    }


    public function getProductsByCategory($category_id)
    {
        $sql = "SELECT * FROM products WHERE products.category_id = {$category_id} AND products.status=1";
        $result = $this->querySql($sql);
        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $products;
        }
        return [];
    }

    public function getProductsByPrice($minPrice, $maxPrice)
    {
        $sql = "SELECT * FROM products WHERE price BETWEEN {$minPrice} AND {$maxPrice} AND products.status = 1";
        $result = $this->querySql($sql);
        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $products;
        }
        return [];
    }

    public function getRelatedProducts($id, $category_id)
    {
        $sql = "SELECT * FROM products WHERE category_id = {$category_id} AND product_id != {$id} AND status = 1";
        $result = $this->querySql($sql);
        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $products;
        }
        return [];
    }

    public function searchProduct($name)
    {
        $sql = " SELECT * FROM products 
        WHERE products.Status = 1 
        AND products.name like '%{$name}%'";
        return $this->querySql($sql);
    }

    public function updateQuantity($productId, $quantityToDeduct)
    {
        $sql = "UPDATE products SET quantity = quantity - {$quantityToDeduct} WHERE product_id = {$productId}";
        return $this->querySql($sql);
    }

    // Lấy danh sách đánh giá sản phẩm
    public function getProductReviews($productId)
    {
        $sql = "SELECT * FROM product_reviews WHERE product_id = {$productId} AND `status` = 1 ORDER BY created_at DESC";
    
        $result = $this->querySql($sql);
        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return [];
    }

    // Thêm đánh giá sản phẩm mới     
    
    public function addProductReview($product_id, $customer_id, $content, $rate) 
    {
        $content = mysqli_real_escape_string($this->connect, $content);

        $sql = "INSERT INTO product_reviews (product_id, customer_id, content, rate, status, created_at) 
                VALUES ({$product_id}, {$customer_id}, '{$content}', {$rate}, 1, CURRENT_TIMESTAMP)";

        $result = $this->querySql($sql);

        if ($result) {
            $sql = "SELECT pr.*, c.customer_name as customer_name 
                    FROM product_reviews pr
                    JOIN customers c ON pr.customer_id = c.customer_id
                    WHERE pr.product_id = {$product_id} 
                    AND pr.customer_id = {$customer_id}
                    ORDER BY pr.created_at DESC 
                    LIMIT 1";
                    
            $review_result = $this->querySql($sql);
            if ($review_result) {
                return mysqli_fetch_assoc($review_result);
            }
        }
        
        return false;
    }

    // Cập nhật đánh giá sản phẩm
    // public function updateProductReview($reviewId, $content, $rate)
    // {
    //     $sql = "UPDATE product_reviews 
    //             SET content = '{$content}', rate = {$rate} 
    //             WHERE product_review_id = {$reviewId}";
    //     return $this->querySql($sql);
    // }

    // // Xóa đánh giá sản phẩm
    // public function deleteProductReview($reviewId)
    // {
    //     $sql = "DELETE FROM product_reviews WHERE product_review_id = {$reviewId}";
    //     return $this->querySql($sql);
    // }

    // Lấy đánh giá của người dùng hiện tại cho một sản phẩm cụ thể
    public function getUserProductReview($product_id, $customer_id)
    {
        $sql = "SELECT * FROM product_reviews 
                WHERE product_id = {$product_id} 
                AND customer_id = {$customer_id}";
        $result = $this->querySql($sql);
        if ($result) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
}
