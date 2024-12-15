<?php
class ConversationModel extends BaseModel
{
    // Định nghĩa tên bảng mà lớp này sẽ tương tác
    const TableName = 'conversations';
 
    // Phương thức này lấy tất cả các cuộc trò chuyện
    public function getConversations()
    {
        try {
            // Truy vấn SQL lấy thông tin cuộc trò chuyện, bao gồm các chi tiết về tin nhắn và khách hàng
            $sql = "SELECT c.conversation_id, c.last_message_at,
                        dc.id as message_id, dc.content, dc.sender_type, 
                        dc.created_at as message_created_at,
                        cus.customer_name, cus.customer_id
                    FROM conversations c
                    LEFT JOIN details_conversations dc ON c.conversation_id = dc.conversation_id 
                    LEFT JOIN customers cus ON cus.customer_id = c.customer_id
                    WHERE c.deleted_by IS NULL 
                    ORDER BY c.last_message_at DESC";

            $result = $this->querySql($sql); // Thực thi truy vấn SQL
            if ($result) {
                $conversations = [];
                 // Duyệt qua từng dòng kết quả và nhóm các cuộc trò chuyện lại theo conversation_id
                while ($row = mysqli_fetch_assoc($result)) {
                    $conversationId = $row['conversation_id'];

                    if (!isset($conversations[$conversationId])) {
                        $conversations[$conversationId] = [
                            'conversation_id' => $conversationId,
                            'customer_name' => $row['customer_name'] ?? 'Ẩn danh',
                            'customer_id' => $row['customer_id'],
                            'last_message_at' => $row['last_message_at'],
                            'last_message' => null
                        ];
                    }

                    // Nếu có tin nhắn mới và tin nhắn này được gửi sau tin nhắn trước đó, cập nhật tin nhắn cuối cùng
                    if ($row['message_id'] && (!$conversations[$conversationId]['last_message'] || 
                        $row['message_created_at'] > $conversations[$conversationId]['last_message']['created_at'])) {
                        $conversations[$conversationId]['last_message'] = [
                            'id' => $row['message_id'],
                            'content' => $row['content'],
                            'sender_type' => $row['sender_type'],
                            'created_at' => $row['message_created_at']
                        ];
                    }
                }

                mysqli_free_result($result); // Giải phóng bộ nhớ sau khi truy vấn xong
                return array_values($conversations); // Trả về mảng các cuộc trò chuyện
            }
        } catch (Exception $e) {
            // Log lỗi nếu có exception xảy ra
            error_log("Get conversations error: " . $e->getMessage());
        }

        // Nếu có lỗi hoặc không có kết quả, trả về mảng rỗng
        return [];
    }

    // Phương thức này lấy thông tin chi tiết của một cuộc trò chuyện theo ID
    public function getConversation($id)
    {
        try {
            // Truy vấn SQL lấy các thông tin chi tiết cuộc trò chuyện theo conversation_id
            $sql = "SELECT c.conversation_id, c.customer_id,
                        dc.id, dc.content, dc.sender_type, dc.receiver_type, 
                        dc.created_at, dc.deleted_by,
                        cus.customer_name
                    FROM conversations c
                    LEFT JOIN details_conversations dc ON c.conversation_id = dc.conversation_id
                    LEFT JOIN customers cus ON cus.customer_id = c.customer_id
                    WHERE c.conversation_id = ?
                    AND c.deleted_by IS NULL
                    AND dc.deleted_by IS NULL
                    ORDER BY dc.created_at ASC";
 
            // Sử dụng prepared statement để ngăn chặn SQL Injection
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("s", $id); // Liên kết tham số vào câu truy vấn
            $stmt->execute(); // Thực thi truy vấn
            $result = $stmt->get_result(); // Lấy kết quả
            
            if (!$result) {
                return [];
            }
            // Trả về kết quả dưới dạng mảng các cuộc trò chuyện
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            // Log lỗi nếu có exception xảy ra
            error_log("Get conversation error: " . $e->getMessage());
            return [];
        }
    }

     // Phương thức này lấy cuộc trò chuyện của một khách hàng theo customer_id
    public function getConversationByCustomer($customer_id) 
    {
        try {
            // Truy vấn SQL lấy thông tin cuộc trò chuyện của một khách hàng theo customer_id
            $sql = "SELECT c.*, dc.*, cus.customer_name
                    FROM conversations c
                    LEFT JOIN details_conversations dc ON c.conversation_id = dc.conversation_id 
                    LEFT JOIN customers cus ON cus.customer_id = c.customer_id
                    WHERE c.customer_id = ?
                    AND c.deleted_by IS NULL
                    ORDER BY dc.created_at ASC";
            
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if (!$result) {
                return [];
            }
            // Trả về kết quả dưới dạng mảng
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Get conversation by customer error: " . $e->getMessage());
            return [];
        }
    }

    // Phương thức này tạo một cuộc trò chuyện mới mà không có khách hàng (NULL customer_id)
    public function createConversationNullCustomer()
    {
        try {
            $conversationId = uniqid('conv_'); // Tạo một conversation_id duy nhất
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentTime = date('Y-m-d H:i:s');  // Lấy thời gian hiện tại

            // Truy vấn SQL để tạo một cuộc trò chuyện mới mà không có khách hàng
            $sql = "INSERT INTO conversations (conversation_id, customer_id, created_at, last_message_at) 
                    VALUES (?, NULL, ?, ?)";

            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("sss", $conversationId, $currentTime, $currentTime);
            return $stmt->execute(); // Thực thi truy vấn và trả về kết quả
        } catch (Exception $e) {
            error_log("Create conversation error: " . $e->getMessage());
            return false;
        }
    }

     // Phương thức này tạo một cuộc trò chuyện mới với một khách hàng cụ thể
    public function createConversationWithCustomer($customer_id)
    {
        try {
            $conversationId = uniqid('conv_'); // Tạo một conversation_id duy nhất
            $user_id = $this->getCurrentAdminId(); // Lấy ID admin hiện tại
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentTime = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại
            
            // Truy vấn SQL để tạo một cuộc trò chuyện mới với khách hàng
            $sql = "INSERT INTO conversations (conversation_id, customer_id, created_by, created_at, last_message_at) 
                    VALUES (?, ?, ?, ?, ?)";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("siiss", $conversationId, $customer_id, $user_id, $currentTime, $currentTime);
            return $stmt->execute(); // Thực thi truy vấn và trả về kết quả
        } catch (Exception $e) {
            error_log("Create conversation with customer error: " . $e->getMessage());
            return false;
        }
    }

    // Phương thức này lấy cuộc trò chuyện mới nhất
    public function getLastestConversation() 
    {
        try {
            // Truy vấn SQL lấy cuộc trò chuyện mới nhất
            $sql = "SELECT * FROM conversations ORDER BY created_at DESC LIMIT 1";
            $result = $this->querySql($sql); // Thực thi truy vấn SQL
            if (!$result) {
                return null;
            }
            // Trả về thông tin cuộc trò chuyện mới nhất
            return mysqli_fetch_assoc($result);
        } catch (Exception $e) {
            error_log("Get latest conversation error: " . $e->getMessage());
            return null;
        }
    }

    // Phương thức này cập nhật thời gian của tin nhắn cuối cùng trong cuộc trò chuyện
    public function updateLastMessageTime($conversation_id)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentTime = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại
            
             // Truy vấn SQL cập nhật thời gian tin nhắn cuối cùng trong cuộc trò chuyện
            $sql = "UPDATE conversations SET last_message_at = ? 
                    WHERE conversation_id = ?";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("ss", $currentTime, $conversation_id);
            return $stmt->execute(); // Thực thi truy vấn và trả về kết quả
        } catch (Exception $e) {
            error_log("Update last message time error: " . $e->getMessage());
            return false;
        }
    }
}