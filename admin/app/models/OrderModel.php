<?php
class OrderModel extends BaseModel
{
    const TableName = 'orders'; // Định nghĩa tên bảng "orders" trong cơ sở dữ liệu

    // Lấy ID người dùng đang đăng nhập từ session, nếu không có thì trả về giá trị mặc định là 1
    public function getUserId()
    {
        return isset($_SESSION['auth_admin']['user_id']) ? $_SESSION['auth_admin']['user_id'] : 1;
    }

    /**
     * Lấy thông tin chi tiết của tất cả các đơn hàng cùng với chi tiết sản phẩm và danh mục liên quan.
     * Phương thức này thực hiện truy vấn đến cơ sở dữ liệu để lấy thông tin của các đơn hàng,
     * chi tiết đơn hàng, sản phẩm và danh mục sản phẩm. Kết quả trả về là một mảng các đơn hàng,
     * mỗi đơn hàng chứa thông tin chi tiết về người nhận, giá trị đơn hàng, trạng thái,
     * và danh sách chi tiết sản phẩm trong đơn hàng đó.
     * @return array Mảng chứa thông tin của tất cả các đơn hàng.
     */
    public function getOrders()
    {
        // Truy vấn SQL lấy tất cả các thông tin đơn hàng, chi tiết sản phẩm, và danh mục liên quan
        $sql = "SELECT 
                o.order_id,
                o.customer_id, 
                o.promotion_id,
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
                p.description,
                p.image,
                p.price,
                c.category_id,
                c.category_name
            FROM orders AS o
            JOIN order_details AS od ON o.order_id = od.order_id
            JOIN products AS p ON od.product_id = p.product_id 
            JOIN categories AS c ON p.category_id = c.category_id
            WHERE o.deleted_by IS NULL
            ORDER BY FIELD(o.status, 'đang chờ', 'đang giao', 'đã giao', 'đã hủy') ASC,
            o.created_at DESC";

        // Thực thi câu truy vấn SQL và lấy kết quả
        $result = $this->querySql($sql);
        if (!$result) return []; // Nếu không có kết quả, trả về mảng rỗng

        $orders = []; // Mảng lưu trữ thông tin các đơn hàng
        while ($row = mysqli_fetch_assoc($result)) {
            $orderId = $row['order_id']; // Lấy ID đơn hàng
            if (!isset($orders[$orderId])) {
                // Nếu đơn hàng chưa có trong mảng $orders, thêm một đơn hàng mới
                $orders[$orderId] = [
                    'order_id' => $orderId,
                    'customer_id' => $row['customer_id'],
                    'promotion_id' => $row['promotion_id'], 
                    'name_receiver' => $row['name_receiver'],
                    'phone_receiver' => $row['phone_receiver'],
                    'address_receiver' => $row['address_receiver'],
                    'notes' => $row['notes'],
                    'total_price' => $row['total_price'],
                    'status' => $row['status'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'orderDetail' => [] // Danh sách chi tiết sản phẩm trong đơn hàng
                ];
            }
            
             // Thêm chi tiết sản phẩm vào mảng "orderDetail" của đơn hàng
            $orders[$orderId]['orderDetail'][] = [
                'order_detail_id' => $orderId . '_' . $row['product_id'], // Tạo một ID duy nhất cho chi tiết đơn hàng
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'product' => [
                    'product_name' => $row['product_name'],
                    'description' => $row['description'], 
                    'price' => $row['price'],
                    'image' => $row['image'],
                    'categories' => [
                        'category_id' => $row['category_id'],
                        'category_name' => $row['category_name']
                    ]
                ]
            ];
        }

        mysqli_free_result($result); // Giải phóng bộ nhớ
        return array_values($orders); // Trả về mảng các đơn hàng
    }

    /**
     * Lấy thông tin chi tiết của một đơn hàng dựa trên ID đơn hàng.
     * 
     * Phương thức này sử dụng ID đơn hàng để truy vấn cơ sở dữ liệu và lấy thông tin chi tiết của đơn hàng đó.
     * Kết quả trả về là một mảng kết hợp chứa thông tin của đơn hàng.
     *
     * @param string $id ID của đơn hàng cần lấy thông tin.
     * @return array|null Mảng chứa thông tin của đơn hàng, hoặc null nếu không tìm thấy.
     */
    public function getOrder($id)
    {
         // Truy vấn SQL lấy thông tin đơn hàng theo ID
        $sql = "SELECT o.* FROM orders as o WHERE o.order_id = '{$id}'";
        $result = $this->querySql($sql);
        return mysqli_fetch_assoc($result); // Trả về thông tin của đơn hàng
    }

    /**
     * Cập nhật trạng thái của đơn hàng thành 'đang giao'.
     * Phương thức này cập nhật trạng thái của đơn hàng trong cơ sở dữ liệu dựa trên ID đơn hàng được cung cấp.
     * Trạng thái của đơn hàng sẽ được cập nhật thành 'đang giao'.
     * @param string $id ID của đơn hàng cần cập nhật trạng thái.
     * @return bool Trả về true nếu cập nhật thành công, false nếu thất bại.
     */
    public function updateStatuShipping($id)
    {
        $user_id = $this->getUserId(); // Lấy ID của người dùng đang đăng nhập
        $sql = "UPDATE " . self::TableName . " SET status = 'đang giao', updated_by={$user_id} WHERE order_id = '{$id}'";
        $result = $this->querySql($sql); // Thực hiện câu lệnh UPDATE
        return $result; // Trả về kết quả của câu lệnh UPDATE
    }

    /**
     * Cập nhật trạng thái của đơn hàng thành 'đã giao'.
     * Cập nhật điểm cho khách hàng dựa trên số lượng sản phẩm trong đơn hàng.
     * @param string $id ID của đơn hàng cần cập nhật trạng thái.
     * @return bool Trả về true nếu cập nhật thành công, false nếu thất bại.
     */
    // public function updateStatusCompleted($id)
    // {
    //     $user_id = $this->getUserId();

    //     // Lấy số lượng order detail và customer_id từ orderID
    //     $sql_order = "SELECT o.customer_id, COUNT(od.order_detail_id) as detail_count 
    //                   FROM orders o 
    //                   JOIN order_details od ON o.order_id = od.order_id 
    //                   WHERE o.order_id = '{$id}'";
    //     $result_order = $this->querySql($sql_order);
    //     $order_info = mysqli_fetch_assoc($result_order);
    //     $detail_count = $order_info['detail_count'];
    //     $customer_id = $order_info['customer_id'];

    //     // Cập nhật trạng thái đơn hàng và thêm số lượng chi tiết đơn hàng
    //     $sql_update_order = "UPDATE " . self::TableName . " SET status = 'đã giao', updated_by={$user_id} WHERE order_id = '{$id}'";
    //     $result_update_order = $this->querySql($sql_update_order);

    //     // Tính toán và cập nhật điểm cho khách hàng
    //     $points_to_add = 20 * $detail_count;
    //     $sql_update_customer = "UPDATE customers SET customer_points = customer_points + {$points_to_add} WHERE customer_id = '{$customer_id}'";
    //     $result_update_customer = $this->querySql($sql_update_customer);

    //     return $result_update_order && $result_update_customer;
    // }

    /**
     * Cập nhật trạng thái của đơn hàng thành 'đã giao'.
     * Cập nhật điểm cho khách hàng dựa trên số lượng sản phẩm trong đơn hàng.
     * @param string $id ID của đơn hàng cần cập nhật trạng thái.
     * @return bool Trả về true nếu cập nhật thành công, false nếu thất bại.
     */
    public function updateStatusCompleted($id)
    {
        try {
            $this->beginTransaction();

            $userId = $this->getUserId(); // Lấy ID người dùng đang đăng nhập
            // Truy vấn để lấy thông tin số lượng chi tiết đơn hàng và ID khách hàng
            $sql = "SELECT o.customer_id, COUNT(od.product_id) as detail_count 
                    FROM orders o 
                    JOIN order_details od ON o.order_id = od.order_id 
                    WHERE o.order_id = '{$id}'";
            
            $result = $this->querySql($sql);
            $orderInfo = mysqli_fetch_assoc($result);
            
            $points = 20 * $orderInfo['detail_count']; // Tính điểm thưởng cho khách hàng mỗi sản phẩm trong đơn hàng sẽ được quy đổi thành 20 điểm
            
            //  // Cập nhật trạng thái đơn hàng thành 'đã giao'
            $this->update(self::TableName, 'order_id', $id, [
                'status' => 'đã giao',
                'updated_by' => $userId
            ]);

            // Cập nhật điểm khách hàng
            $sql = "UPDATE customers 
                    SET customer_points = customer_points + {$points} 
                    WHERE customer_id = '{$orderInfo['customer_id']}'";
            $this->querySql($sql);

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            error_log("Update Status Completed Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật trạng thái của đơn hàng thành 'đã hủy'.
     * Tương tự như các phương thức cập nhật trạng thái khác, nhưng trạng thái được cập nhật thành 'đã hủy'.
     * @param string $id ID của đơn hàng cần cập nhật trạng thái.
     * @return bool Trả về true nếu cập nhật thành công, false nếu thất bại.
     */
    public function updateStatusCancle($id)
    {
        $user_id = $this->getUserId(); // Lấy ID người dùng đang đăng nhập
        $sql = "UPDATE " . self::TableName . " SET status = 'đã hủy', updated_by={$user_id} WHERE order_id = '{$id}'";
        $result = $this->querySql($sql); // Thực hiện câu lệnh UPDATE
        return $result; // Trả về kết quả của câu lệnh UPDATE
    }

    /**
     * Tính toán và lấy tổng doanh thu từ các đơn hàng trong ngày hiện tại.
     * Phương thức này truy vấn cơ sở dữ liệu để tính tổng giá trị của tất cả các đơn hàng đã được tạo trong ngày hiện tại.
     * Kết quả trả về là tổng doanh thu.
     * @return array Mảng chứa tổng doanh thu trong ngày hiện tại.
     */
    // public function getRevenueToday()
    // {
    //     $today = (new DateTime())->format('Y-m-d');
    //     $sql = "SELECT SUM(total_price) AS revenueToday FROM orders WHERE DATE(created_at) = '{$today}' AND orders.status = 'đã giao'";

    //     $result = $this->querySql($sql);
    //     return mysqli_fetch_array($result);
    // }

    /**
     * Tính toán và lấy tổng doanh thu từ các đơn hàng trong ngày hiện tại.
     * Phương thức này truy vấn cơ sở dữ liệu để tính tổng giá trị của tất cả các đơn hàng đã được tạo trong ngày hiện tại.
     * Kết quả trả về là tổng doanh thu.
     * @return array Mảng chứa tổng doanh thu trong ngày hiện tại.
     */
    public function getRevenueToday()
    {
        try {
            $today = (new DateTime())->format('Y-m-d'); // Lấy ngày hôm nay
             // Truy vấn SQL tính tổng doanh thu trong ngày hôm nay từ các đơn hàng đã giao
            $sql = "SELECT COALESCE(SUM(total_price), 0) AS revenueToday 
                    FROM orders 
                    WHERE DATE(created_at) = '{$today}' 
                    AND status = 'đã giao' 
                    AND deleted_by IS NULL";
    
            $result = $this->querySql($sql);
            return mysqli_fetch_array($result); // Trả về tổng doanh thu hôm nay
        } catch (\Throwable $th) {
            error_log("Get Revenue Today Error: " . $th->getMessage()); 
            return ['revenueToday' => 0]; // Trả về 0 nếu có lỗi
        }
    }
    // public function getTotalRevenue()
    // {
    //     $sql = "SELECT SUM(total_price) AS revenueToday FROM orders WHERE orders.status = 'đã giao'";

    //     $result = $this->querySql($sql);
    //     return mysqli_fetch_array($result);
    // }

     /**
     * Lấy tổng doanh thu từ các đơn hàng đã giao.
     * Phương thức này truy vấn cơ sở dữ liệu để tính tổng doanh thu từ các đơn hàng có trạng thái 'đã giao'
     * và không bị xóa (deleted_by là NULL).
     * @return array Mảng chứa tổng doanh thu từ các đơn hàng đã giao.
     */
    public function getTotalRevenue() 
    {
        // Câu lệnh SQL tính tổng doanh thu từ các đơn hàng đã giao
        $sql = "SELECT COALESCE(SUM(total_price), 0) AS totalRevenue 
                FROM orders 
                WHERE status = 'đã giao' AND deleted_by IS NULL";

        // Thực thi câu truy vấn SQL
        $result = $this->querySql($sql);
        return mysqli_fetch_array($result);  // Trả về kết quả là tổng doanh thu
    }

    /**
     * Lấy thông tin của các đơn hàng được tạo trong ngày hiện tại, giới hạn ở 6 đơn hàng.
     * Phương thức này truy vấn cơ sở dữ liệu để lấy thông tin của các đơn hàng được tạo trong ngày hiện tại.
     * Số lượng đơn hàng trả về được giới hạn là 6.
     * @return array Mảng chứa thông tin của các đơn hàng trong ngày hiện tại.
     */
    public function getTransactionToday()
    {
        $today = (new DateTime())->format('Y-m-d'); // Lấy ngày hôm nay
        // Câu lệnh SQL truy vấn các đơn hàng được tạo trong ngày hôm nay, giới hạn 6 đơn hàng
        $sql = "SELECT o.*
            FROM orders as o
            WHERE DATE(o.created_at) = '{$today}'
            LIMIT 6
            ";
        $result = $this->querySql($sql);  // Thực thi câu truy vấn SQL
        // Nếu có kết quả, trả về mảng các đơn hàng
        if ($result) {
            $transaction = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $transaction;
        }

        return []; // Trả về mảng rỗng nếu không có kết quả
    }

    /**
     * Lấy dữ liệu biểu đồ cho các đơn hàng và doanh thu theo ngày.
     * Phương thức này truy vấn cơ sở dữ liệu để lấy số lượng đơn hàng và tổng doanh thu theo ngày.
     * Có thể lọc theo khoảng thời gian nếu cung cấp ngày bắt đầu và kết thúc.
     * @param string|null $startDate Ngày bắt đầu của khoảng thời gian (nếu có).
     * @param string|null $endDate Ngày kết thúc của khoảng thời gian (nếu có).
     * @return array Mảng chứa dữ liệu biểu đồ theo ngày.
     */
    public function getChartData()
    {
        // Đặt ngày bắt đầu và kết thúc cho khoảng thời gian mặc định
        $default_start_date = (new DateTime())->format('Y-m-d H:i:s');
        $default_end_date = (new DateTime())->format('Y-m-d 23:59:59');

        // Câu lệnh SQL lấy dữ liệu biểu đồ cho các đơn hàng đã giao trong khoảng thời gian từ ngày bắt đầu đến kết thúc
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as total_orders, SUM(total_price) as total_revenue 
        FROM orders 
        WHERE created_at BETWEEN '{$default_start_date}' AND '{$default_end_date}' 
        AND orders.status = 'đã giao'
        GROUP BY date 
        ORDER BY date";

        $result = $this->querySql($sql);  // Thực thi câu truy vấn SQL
        // Nếu có kết quả, trả về dữ liệu biểu đồ
        if ($result) {
            $chartData = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $chartData;
        }
        return []; // Trả về mảng rỗng nếu không có kết quả
    } 

    /**
     * Lấy dữ liệu biểu đồ cho các đơn hàng và doanh thu theo ngày trong khoảng thời gian cụ thể
     * 
     * Phương thức này truy vấn cơ sở dữ liệu để lấy số lượng đơn hàng và tổng doanh thu theo ngày.
     * Có thể lọc theo khoảng thời gian nếu cung cấp ngày bắt đầu và kết thúc.
     *
     * @param string|null $startDate Ngày bắt đầu của khoảng thời gian (nếu có).
     * @param string|null $endDate Ngày kết thúc của khoảng thời gian (nếu có).
     * @return array Mảng chứa dữ liệu biểu đồ theo ngày.
     */
    public function getChartDataFiltered($startDate, $endDate)
    {
        // Câu lệnh SQL lấy dữ liệu biểu đồ cho các đơn hàng đã giao trong khoảng thời gian từ $startDate đến $endDate
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as total_orders, SUM(total_price) as total_revenue 
        FROM orders 
        WHERE created_at BETWEEN '{$startDate}' AND '{$endDate}' AND orders.status = 'đã giao'
        GROUP BY date 
        ORDER BY date";

        $result = $this->querySql($sql);
        if ($result) {
            $chartData = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $chartData;
        }
        return [];
    }
    public function updateOrderStatus($orderId, $newStatus)
    {
        try {
            // Lấy ID người dùng hiện tại từ session (nếu cần)
            $userId = $this->getUserId(); 

            // Cập nhật trạng thái đơn hàng thành "đã giao"
            if ($newStatus == 'đã giao') {
                // Lấy thông tin đơn hàng để tính điểm thưởng
                $sql = "SELECT o.customer_id, COUNT(od.product_id) as detail_count 
                        FROM orders o 
                        JOIN order_details od ON o.order_id = od.order_id 
                        WHERE o.order_id = '{$orderId}'";
                $result = $this->querySql($sql);
                $orderInfo = mysqli_fetch_assoc($result);
                $points = 20 * $orderInfo['detail_count']; // Tính điểm thưởng cho khách hàng

                // Cập nhật trạng thái đơn hàng
                $sql_update_order = "UPDATE orders 
                                    SET status = 'đã giao', updated_by = '{$userId}', updated_at = NOW() 
                                    WHERE order_id = '{$orderId}'";
                $this->querySql($sql_update_order);

                // Cập nhật điểm cho khách hàng
                $sql_update_customer = "UPDATE customers 
                                        SET customer_points = customer_points + {$points} 
                                        WHERE customer_id = '{$orderInfo['customer_id']}'";
                $this->querySql($sql_update_customer);
            } else {
                // Nếu không phải "đã giao", chỉ cập nhật trạng thái đơn hàng
                $sql = "UPDATE orders 
                        SET status = '{$newStatus}', updated_by = '{$userId}', updated_at = NOW() 
                        WHERE order_id = '{$orderId}'";
                $this->querySql($sql);
            }

            return true; // Trả về true nếu thành công
        } catch (Exception $e) {
            error_log("Error updating order status: " . $e->getMessage());
            return false; // Trả về false nếu có lỗi
        }
    }

}
