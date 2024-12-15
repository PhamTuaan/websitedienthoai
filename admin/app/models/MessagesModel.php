<?php
class MessagesModel extends BaseModel
{
    const TableName = 'messages';

    // Phương thức này tạo một tin nhắn mới trong bảng 'details_conversations'
    public function createMessage($data)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentTime = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại
    
            // Truy vấn SQL để chèn dữ liệu tin nhắn vào bảng details_conversations
            $sql = "INSERT INTO details_conversations 
                    (conversation_id, content, sender_type, receiver_type, sender_id, receiver_id, created_at, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Chuẩn bị câu lệnh SQL
            $stmt = $this->connect->prepare($sql);
            if (!$stmt) {
                error_log("Prepare statement failed: " . $this->connect->error); // Log lỗi nếu không thể chuẩn bị câu lệnh
                return null;
            }
    
            // Đối với tin nhắn của quản trị viên, sender_id phải là NULL vì nó tham chiếu customer_id
            // $senderId = ($data['sender_type'] === 'admin') ? null : $data['created_by'];
            // Đặt giá trị cho các tham số trong câu lệnh SQL
            $receiverId = null; // Chưa xác định người nhận
            $senderId = $data['created_by'];
            $stmt->bind_param("ssssiisi", 
                $data['conversation_id'], // ID cuộc trò chuyện
                $data['content'], // Nội dung tin nhắn
                $data['sender_type'], // Loại người gửi (admin, customer...)
                $data['receiver_type'], // Loại người nhận
                $senderId,   // ID người gửi
                $receiverId, // ID người nhận (null cho admin)
                $currentTime, // Thời gian tạo tin nhắn
                $data['created_by']  // ID người tạo tin nhắn
            );
            // Thực thi câu lệnh SQL
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error); // Log lỗi nếu không thể thực thi câu lệnh
                return null;
            }
    
            // Lấy ID của bản ghi vừa được chèn
            $lastInsertId = $stmt->insert_id;
            if ($lastInsertId) {
                 // Nếu chèn thành công, cập nhật thời gian của tin nhắn cuối cùng trong cuộc trò chuyện
                $this->updateLastMessageTime($data['conversation_id']);
                return ['id' => $lastInsertId]; // Trả về ID của tin nhắn vừa tạo
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Create message error: " . $e->getMessage());
            return null;
        }
    }
   
    // Phương thức này đánh dấu tin nhắn là đã đọc
    public function updateMessage($id)
    {
        try {
            // Truy vấn SQL để cập nhật thời gian đọc tin nhắn
            $sql = "UPDATE details_conversations 
                    SET read_at = NOW() 
                    WHERE id = ? AND read_at IS NULL";
            
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update message error: " . $e->getMessage());
            return false;
        }
    }

     // Phương thức này xóa tin nhắn
    public function deleteMessage($id)
    {
        try {
            // Lấy ID người quản trị hiện tại từ session
            $user_id = $this->getCurrentAdminId();
            // Truy vấn SQL để đánh dấu tin nhắn là đã bị xóa
            $sql = "UPDATE details_conversations 
                    SET deleted_by = ?,
                        updated_at = NOW() 
                    WHERE id = ?";
            
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("ii", $user_id, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Delete message error: " . $e->getMessage());
            return false;
        }
    }

    // Phương thức này cập nhật thời gian của tin nhắn cuối cùng trong cuộc trò chuyện
    private function updateLastMessageTime($conversation_id)
    {
        try {
            // Truy vấn SQL để cập nhật thời gian của tin nhắn cuối cùng trong cuộc trò chuyện
            $sql = "UPDATE conversations 
                    SET last_message_at = NOW() 
                    WHERE conversation_id = ?";
            
            // Chuẩn bị câu lệnh SQL
            $stmt = $this->connect->prepare($sql); 
            $stmt->bind_param("s", $conversation_id); // Ràng buộc ID cuộc trò chuyện
            return $stmt->execute(); // Thực thi câu lệnh và trả về kết quả
        } catch (Exception $e) {
            error_log("Update last message time error: " . $e->getMessage()); // Log lỗi nếu có lỗi ngoại lệ
            return false;
        }
    }
}
