<?php
class ConversationModel extends BaseModel
{
    const TableName = 'conversations';
    
    private function getCurrentTime()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        return date('Y-m-d H:i:s');
    }

    public function getConversations()
    {
        try {
            $sql = "SELECT c.conversation_id, c.last_message_at,
                        dc.id as message_id, dc.content, dc.sender_type, 
                        dc.created_at as message_created_at,
                        cus.customer_name, cus.customer_id
                    FROM conversations c
                    LEFT JOIN details_conversations dc ON c.conversation_id = dc.conversation_id 
                    LEFT JOIN customers cus ON cus.customer_id = c.customer_id
                    WHERE c.deleted_by IS NULL 
                    ORDER BY c.last_message_at DESC";

            $result = $this->querySql($sql);
            if ($result) {
                $conversations = [];
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

                mysqli_free_result($result);
                return array_values($conversations);
            }
        } catch (Exception $e) {
            error_log("Get conversations error: " . $e->getMessage());
        }

        return [];
    }

    public function getConversation($id)
    {
        try {
            // Using proper prepared statement syntax
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

            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if (!$result) {
                return [];
            }
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Get conversation error: " . $e->getMessage());
            return [];
        }
    }

    public function getConversationByCustomer($customer_id) 
    {
        try {
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
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Get conversation by customer error: " . $e->getMessage());
            return [];
        }
    }

    public function createConversationNullCustomer()
    {
        try {
            $conversationId = uniqid('conv_');
            $currentTime = $this->getCurrentTime();
            
            $sql = "INSERT INTO conversations (conversation_id, customer_id, created_at, last_message_at) 
                    VALUES (?, NULL, ?, ?)";

            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("sss", $conversationId, $currentTime, $currentTime);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Create conversation error: " . $e->getMessage());
            return false;
        }
    }

    public function createConversationWithCustomer($customer_id)
    {
        try {
            $existingConversation = $this->getConversationByCustomer($customer_id);
            if (!empty($existingConversation)) {
                return $existingConversation[0]['conversation_id'];
            }

            $conversationId = 'CONV' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $currentTime = $this->getCurrentTime();
            
            $sql = "INSERT INTO conversations (conversation_id, customer_id, created_at, last_message_at) 
                    VALUES (?, ?, ?, ?)";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("siss", $conversationId, $customer_id, $currentTime, $currentTime);
            
            if (!$stmt->execute()) {
                error_log("Execute Error: " . $stmt->error); 
                return false;
            }

            return $conversationId;
        } catch (Exception $e) {
            error_log("Create conversation error: " . $e->getMessage());
            return false;
        }
    }

    public function getLastestConversation() 
    {
        try {
            $sql = "SELECT * FROM conversations ORDER BY created_at DESC LIMIT 1";
            $result = $this->querySql($sql);
            if (!$result) {
                return null;
            }
            return mysqli_fetch_assoc($result);
        } catch (Exception $e) {
            error_log("Get latest conversation error: " . $e->getMessage());
            return null;
        }
    }

    public function updateLastMessageTime($conversation_id)
    {
        try {
            $currentTime = $this->getCurrentTime();
            
            $sql = "UPDATE conversations SET last_message_at = ? 
                    WHERE conversation_id = ?";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("ss", $currentTime, $conversation_id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update last message time error: " . $e->getMessage());
            return false;
        }
    }
    
    public function storeConversationByCustomer($id)
    {
        try {
            $existingConversation = $this->getConversationByCustomer($id);
            
            if (!empty($existingConversation)) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Đã có conversation',
                    'data' => ['conversation_id' => $existingConversation[0]['conversation_id']]
                ]);
                return;
            }
    
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentTime = date('Y-m-d H:i:s');
            $conversationId = uniqid('conv_');
            
            $sql = "INSERT INTO conversations 
                    (conversation_id, customer_id, created_by, created_at, last_message_at) 
                    VALUES (?, ?, ?, ?, ?)";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("siiss", $conversationId, $id, $id, $currentTime, $currentTime);
            
            if (!$stmt->execute()) {
                throw new Exception("Không thể tạo conversation");
            }
            
            echo json_encode([
                'status' => 200,
                'message' => 'Tạo conversation thành công',
                'data' => ['conversation_id' => $conversationId]
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }
}