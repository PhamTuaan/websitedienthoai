<?php
class OrderModel extends BaseModel
{
    const TableName = 'orders';

    public function getOrders()
    {
        $sql = "SELECT 
                o.order_id,
                o.customer_id,
                o.name_receiver,
                o.phone_receiver,
                o.address_receiver,
                o.notes,
                o.total_price AS order_total_price,
                o.status AS order_status,
                o.created_at AS order_created_at,
                o.updated_at AS order_updated_at,
                od.order_detail_id,
                od.product_id,
                od.quantity AS order_detail_quantity,
                od.price AS order_detail_price,
                od.create_at AS order_detail_created_at,
                od.update_at AS order_detail_updated_at,
                p.product_name,
                p.description AS product_description,
                p.image AS product_image,
                p.price AS product_price,
                c.category_id,
                c.category_name
            FROM orders AS o
            JOIN order_details AS od ON o.order_id = od.order_id
            JOIN products AS p ON od.product_id = p.product_id
            JOIN categories AS c ON p.category_id = c.category_id
            ORDER BY FIELD(o.status, 'đang chờ', 'đang giao', 'đã giao', 'đã hủy') ASC, o.created_at DESC";

        $result = $this->querySql($sql);

        if ($result) {
            $orders = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $order_id = $row['order_id'];

                // Tạo mới đối tượng order nếu chưa tồn tại
                if (!isset($orders[$order_id])) {
                    $orders[$order_id] = [
                        'order_id' => $order_id,
                        'customer_id' => $row['customer_id'],
                        'name_receiver' => $row['name_receiver'],
                        'phone_receiver' => $row['phone_receiver'],
                        'address_receiver' => $row['address_receiver'],
                        'notes' => $row['notes'],
                        'total_price' => $row['order_total_price'],
                        'status' => $row['order_status'],
                        'created_at' => $row['order_created_at'],
                        'updated_at' => $row['order_updated_at'],
                        'orderDetail' => []
                    ];
                }

                $orderDetail = [
                    'order_detail_id' => $row['order_detail_id'],
                    'product_id' => $row['product_id'],
                    'quantity' => $row['order_detail_quantity'],
                    'price' => $row['order_detail_price'],
                    'created_at' => $row['order_detail_created_at'],
                    'updated_at' => $row['order_detail_updated_at'],
                    'product' => [
                        'product_name' => $row['product_name'],
                        'description' => $row['product_description'],
                        'price' => $row['product_price'],
                        'image' => $row['product_image'],
                        'categories' => [
                            'category_id' => $row['category_id'],
                            'category_name' => $row['category_name']
                        ]
                    ]
                ];

                $orders[$order_id]['orderDetail'][] = $orderDetail;
            }

            mysqli_free_result($result);

            $final_orders = array_values($orders);

            return $final_orders;
        }

        return [];
    }

    public function getOrder($id)
    {
        $sql = "SELECT o.* FROM orders as o WHERE o.order_id = '{$id}'";
        $result = $this->querySql($sql);
        return mysqli_fetch_assoc($result);
    }

    public function updateStatuShipping($id)
    {
        $sql = "UPDATE " . self::TableName . " SET status = 'đang giao' WHERE order_id = '{$id}'";
        $result = $this->querySql($sql);
        return $result;
    }

    public function updateStatusCompleted($id)
    {
        $sql_order = "SELECT o.customer_id, o.total_price, SUM(od.quantity) as total_quantity 
                      FROM orders o 
                      JOIN order_details od ON o.order_id = od.order_id 
                      WHERE o.order_id = '{$id}'";
        $result_order = $this->querySql($sql_order);
        $order_info = mysqli_fetch_assoc($result_order);
        $total_quantity = $order_info['total_quantity'];
        $customer_id = $order_info['customer_id'];
        $total_price = $order_info['total_price'];
    
        $sql_update_order = "UPDATE " . self::TableName . " SET status = 'đã giao' WHERE order_id = '{$id}'";
        $result_update_order = $this->querySql($sql_update_order);
    
        // Tính toán và cập nhật điểm cho khách hàng
        // Mỗi 1 triệu đồng trong tổng giá trị đơn hàng sẽ nhận được 10 điểm
        $points_to_add = (int)($total_price / 1000000) * 10;
        $sql_update_customer = "UPDATE customers SET customer_points = customer_points + {$points_to_add} WHERE customer_id = '{$customer_id}'";
        $result_update_customer = $this->querySql($sql_update_customer);
    
        return $result_update_order && $result_update_customer;
    }
    

    public function updateStatusCancle($id)
    {
        $sql = "UPDATE " . self::TableName . " SET status = 'đã hủy' WHERE order_id = '{$id}'";
        $result = $this->querySql($sql);
        return $result;
    }

    public function getRevenueToday()
    {
        $today = (new DateTime())->format('Y-m-d');
        $sql = "SELECT SUM(total_price) AS revenueToday FROM orders WHERE DATE(created_at) = '{$today}'";

        $result = $this->querySql($sql);
        return mysqli_fetch_array($result);
    }

    public function getTransactionToday()
    {
        $today = (new DateTime())->format('Y-m-d');
        $sql = "SELECT o.*
            FROM orders as o
            WHERE DATE(o.created_at) = '{$today}'
            LIMIT 6
            ";
        $result = $this->querySql($sql);

        if ($result) {
            $transaction = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $transaction;
        }

        return [];
    }

    public function getOrderHistory($cus_id) {
        error_log("Getting order history for customer ID: " . $cus_id);
        
        $sql = "SELECT 
                o.order_id,
                o.customer_id,
                o.name_receiver,
                o.phone_receiver,
                o.address_receiver,
                o.notes,
                o.total_price,
                o.status,
                o.created_at,
                o.updated_at,
                od.product_id,
                od.quantity,
                p.product_name,
                p.price AS product_price,
                p.image AS product_image,
                p.description AS product_description
            FROM orders o
            LEFT JOIN order_details od ON o.order_id = od.order_id
            LEFT JOIN products p ON od.product_id = p.product_id
            WHERE o.customer_id = ?";
    
        try {
            // Debug query
            error_log("SQL Query: " . $sql);
            error_log("Customer ID param: " . $cus_id);
    
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . $this->db->error);
                return [];
            }
    
            $stmt->bind_param("i", $cus_id);
            
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                return [];
            }
    
            $result = $stmt->get_result();
            
            if (!$result) {
                error_log("Get result failed: " . $stmt->error); 
                return [];
            }
    
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $order_id = $row['order_id'];
                
                // Log each row for debugging
                error_log("Processing row: " . json_encode($row));
                
                if (!isset($orders[$order_id])) {
                    $orders[$order_id] = [
                        'order_id' => $order_id,
                        'customer_id' => $row['customer_id'],
                        'name_receiver' => $row['name_receiver'],
                        'phone_receiver' => $row['phone_receiver'],
                        'address_receiver' => $row['address_receiver'],
                        'notes' => $row['notes'],
                        'total_price' => $row['total_price'],
                        'status' => $row['status'],
                        'created_at' => $row['created_at'],
                        'updated_at' => $row['updated_at'],
                        'orderDetail' => []
                    ];
                }
    
                if ($row['product_id']) {
                    $orders[$order_id]['orderDetail'][] = [
                        'quantity' => $row['quantity'],
                        'product' => [
                            'product_name' => $row['product_name'],
                            'price' => $row['product_price'],
                            'image' => $row['product_image']
                        ]
                    ];
                }
            }
    
            // Log final result
            error_log("Final orders array: " . json_encode(array_values($orders)));
            
            return array_values($orders);
    
        } catch (Exception $e) {
            error_log("Error in getOrderHistory: " . $e->getMessage());
            return [];
        }
    }

    public function getChartData()
    {
        $default_start_date = (new DateTime())->format('Y-m-d H:i:s');
        $default_end_date = (new DateTime())->format('Y-m-d 23:59:59');

        $sql = "SELECT DATE(created_at) as date, COUNT(*) as total_orders, SUM(total_price) as total_revenue 
        FROM orders 
        WHERE created_at BETWEEN '{$default_start_date}' AND '{$default_end_date}' 
        GROUP BY date 
        ORDER BY date";

        $result = $this->querySql($sql);
        if ($result) {
            $chartData = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $chartData;
        }
        return [];
    }

    public function getChartDataFiltered($startDate, $endDate)
    {
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as total_orders, SUM(total_price) as total_revenue 
        FROM orders 
        WHERE created_at BETWEEN '{$startDate}' AND '{$endDate}' 
        GROUP BY date 
        ORDER BY date";

        $result = $this->querySql($sql);
        if ($result) {
            $chartData = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $chartData;
        }
        return [];
    }

    public function getLastestOrder()
    {
        $sql = "SELECT o.* FROM orders as o ORDER BY o.order_id DESC LIMIT 1";
        $result = $this->querySql($sql);
        return mysqli_fetch_assoc($result);
    }

    public function createOrder($data)
    {
        // Thêm kiểm tra promotion_id nếu có
        if(isset($data['promotion_id'])) {
            // Cập nhật trạng thái promotion
            $this->querySql("UPDATE promotions SET promotion_used = 1 WHERE promotion_id = {$data['promotion_id']}");
        }
        return $this->create(self::TableName, $data);
    }
}
