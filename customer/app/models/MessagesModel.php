<?php
class MessagesModel extends BaseModel
{
    const TableName = 'messages';

    public function createMessage($data)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentTime = date('Y-m-d H:i:s');
    
            $sql = "INSERT INTO details_conversations 
                    (conversation_id, content, sender_type, receiver_type, sender_id, receiver_id, created_at, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->connect->prepare($sql);
            if (!$stmt) {
                error_log("Prepare statement failed: " . $this->connect->error);
                return null;
            }
    
            $senderId = ($data['sender_type'] === 'customer') ? $data['sender_id'] : null;
            $receiverId = ($data['receiver_id']) ?? null;
            $createdBy = $data['created_by'] ?? $senderId; 
    
            $stmt->bind_param("ssssiisi", 
                $data['conversation_id'],
                $data['content'],
                $data['sender_type'],
                $data['receiver_type'],
                $senderId,     
                $receiverId,    
                $currentTime,
                $createdBy  
            );
    
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                return null;
            }
    
            $lastInsertId = $stmt->insert_id;
            if ($lastInsertId) {
                $this->updateLastMessageTime($data['conversation_id'], $currentTime);
                return ['id' => $lastInsertId];
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Create message error: " . $e->getMessage());
            return null;
        }
    }
   
    public function updateMessage($id)
    {
        try {
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

    public function deleteMessage($id)
    {
        try {
            $user_id = $this->getCurrentAdminId();
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

    private function updateLastMessageTime($conversation_id, $currentTime)
    {
        try {
            $sql = "UPDATE conversations 
                    SET last_message_at = ? 
                    WHERE conversation_id = ?";
            
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("ss", $currentTime, $conversation_id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update last message time error: " . $e->getMessage());
            return false;
        }
    }
}
