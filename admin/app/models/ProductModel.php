<?php
class ProductModel extends BaseModel
{
    const TableName = 'products';

    public function getUserId()
    {
        return isset($_SESSION['auth_admin']['user_id']) ? $_SESSION['auth_admin']['user_id'] : 1;
    }

    /**
     * Lấy danh sách tất cả sản phẩm kèm thông tin danh mục của chúng.
     * Sản phẩm được sắp xếp theo trạng thái và ngày tạo giảm dần.
     * 
     * @return array Danh sách sản phẩm hoặc mảng rỗng nếu không có sản phẩm nào.
     */
    public function getProducts()
    {
        $sql = "SELECT p.* ,c.category_name,c.category_id FROM products as p
        JOIN categories as c 
        ON p.category_id = c.category_id
        ORDER BY p.status DESC, p.created_at DESC";
        $result = $this->querySql($sql);
        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $products;
        }
        return [];
    }

    /**
     * Lấy thông tin chi tiết của một sản phẩm dựa trên ID.
     * 
     * @param int $id ID của sản phẩm cần lấy thông tin.
     * @return array|null Thông tin sản phẩm hoặc null nếu không tìm thấy.
     */
    public function getProduct($id)
    {
        $sql = "SELECT c.*, c.deleted_by 
                FROM products as c 
                WHERE c.product_id = '{$id}'";
        $result = $this->querySql($sql);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Đếm tổng số sản phẩm có trạng thái hoạt động (status = 1).
     * 
     * @return int Tổng số sản phẩm hoạt động.
     */
    public function getTotalProduct()
    {
        $sql = "SELECT COUNT(*) AS totalProduct FROM products WHERE status = 1";
        $result = $this->querySql($sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['totalProduct'];
        }
        return 0;
    }

    /**
     * Lấy danh sách 10 sản phẩm bán chạy nhất.
     * Sản phẩm được sắp xếp theo số lượng bán ra giảm dần.
     * 
     * @return array Danh sách 10 sản phẩm bán chạy nhất hoặc mảng rỗng nếu không có dữ liệu.
     */
    public function getTop10Seller()
    {
        $sql = "SELECT products.*, SUM(order_details.quantity) AS total_quantity
        FROM products
        JOIN order_details ON order_details.product_id = products.product_id
        JOIN orders ON orders.order_id = order_details.order_id
        -- WHERE orders.status = 'completed'
        GROUP BY products.product_id, products.category_id, products.product_name, products.price, products.quantity, products.description, products.image, products.status, products.created_at, products.updated_at
        ORDER BY total_quantity DESC
        LIMIT 6";

        $result = $this->querySql($sql);

        if ($result) {
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $products;
        }

        return [];
    }

    /**
     * Tạo mới một sản phẩm.
     * 
     * @param array $data Mảng dữ liệu sản phẩm.
     * @return mixed Kết quả thực thi câu lệnh SQL.
     */
    public function createProduct($data)
    {
        return $this->create(self::TableName, $data);
    }

    /**
     * Lấy danh sách sản phẩm theo danh mục.
     * 
     * @param int $category_id ID của danh mục.
     * @return mysqli_result Kết quả truy vấn.
     */
    public function getProductsByCategory($category_id)
    {
        $sql = "SELECT * FROM products WHERE products.category_id = {$category_id} AND products.status=1";
        return $this->querySql($sql);
    }

    /**
     * Cập nhật thông tin sản phẩm.
     * 
     * @param int $id ID của sản phẩm cần cập nhật.
     * @param array $data Mảng dữ liệu cập nhật.
     * @return mixed Kết quả thực thi câu lệnh SQL.
     */
    public function updateProduct($id, $data)
    {
        return $this->update(self::TableName, 'product_id', $id, $data);
    }

    /**
     * Tìm kiếm sản phẩm theo tên.
     * 
     * @param string $name Tên sản phẩm cần tìm kiếm.
     * @return mysqli_result Kết quả truy vấn.
     */
    public function searchProduct($name)
    {
        $sql = " SELECT * FROM products 
        WHERE products.Status = 1 
        AND products.name like '%{$name}%'";
        return $this->querySql($sql);
    }

    /**
     * Đảo trạng thái kích hoạt của sản phẩm (từ hoạt động sang không hoạt động và ngược lại).
     * 
     * @param int $id ID của sản phẩm cần đổi trạng thái.
     * @return mixed Kết quả thực thi câu lệnh SQL.
     */
    public function deleteProduct($id)
    {
        $user_id = $this->getUserId();
        $product = $this->getProduct($id);
        
        $deleted_by = $product['status'] ? $user_id : 'NULL';
        
        $sql = "UPDATE " . self::TableName . " 
                SET status = NOT status, 
                    deleted_by = {$deleted_by} 
                WHERE product_id = '{$id}'";
                
        $result = $this->querySql($sql);
        return $result;
    }

    /**
     * Đếm tổng số sản phẩm có trạng thái hoạt động.
     * 
     * @return int Tổng số sản phẩm hoạt động.
     */
    public function totalProduct()
    {
        $sql = "SELECT COUNT(*) as productNumber FROM products WHERE products.Status = 1";
        $result = mysqli_fetch_array($this->querySql($sql));;
        return $result;
    }

    public function getProductReviews() {
        try {
            $sql = "SELECT 
                    CONCAT(pr.customer_id, '_', pr.product_id) as review_id,
                    pr.customer_id,
                    pr.product_id,
                    pr.content, 
                    c.email, 
                    pr.rate, 
                    pr.status, 
                    DATE_FORMAT(pr.created_at, '%Y-%m-%d %H:%i:%s') as created_at
                FROM product_reviews pr 
                LEFT JOIN customers c ON pr.customer_id = c.customer_id 
                ORDER BY pr.created_at DESC";
                
            error_log("Executing SQL: " . $sql);
            
            $result = $this->querySql($sql);
            
            if (!$result) {
                error_log("SQL Error: " . mysqli_error($this->conn));
                return [
                    'status' => 200,
                    'data' => []
                ];
            }
    
            $reviews = [];
            while ($row = mysqli_fetch_assoc($result)) {
                // Sanitize and validate each field
                $reviews[] = [
                    'review_id' => htmlspecialchars($row['review_id']),
                    'content' => htmlspecialchars($row['content']),
                    'email' => htmlspecialchars($row['email']),
                    'rate' => (int)$row['rate'],
                    'status' => (int)$row['status'],
                    'created_at' => $row['created_at']
                ];
            }
            
            error_log("Found " . count($reviews) . " reviews");
            
            return [
                'status' => 200,
                'data' => $reviews
            ];
            
        } catch (Exception $e) {
            error_log("Error in getProductReviews: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [
                'status' => 500,
                'data' => []
            ];
        }
    }


    public function deleteProductReview($compositeId) {
        try {
            list($customerId, $productId) = explode('_', $compositeId);
            
            $sql = "UPDATE product_reviews 
                    SET status = NOT status 
                    WHERE customer_id = '{$customerId}' 
                    AND product_id = '{$productId}'";
                    
            $result = $this->querySql($sql);
            return $result;
        } catch (Exception $e) {
            error_log("Error in deleteProductReview: " . $e->getMessage());
            return false;
        }
    }
}
