<?php
class ConversationController extends BaseController
{
    private $conversationModel;
    private $messagesModel;

    public function __construct()
    {
        $this->conversationModel = $this->model('ConversationModel');
        $this->messagesModel = $this->model('MessagesModel');
    }

    public function index()
    {
        $conversations = $this->conversationModel->getConversations();
        $this->view('app', [
            'page' => '/conversation/index',
            'title' => 'Thông tin hội thoại',
            'conversations' => $conversations
        ]);
    }

    public function detail($id)
    {
        try {
            $conversations = $this->conversationModel->getConversation($id);
            if (empty($conversations)) {
                $_SESSION['error'] = 'Không tìm thấy cuộc hội thoại';
                header('Location: ' . URL_APP . '/conversation');
                exit;
            }
            
            foreach ($conversations as $con) {
                if (isset($con['id']) && $con['sender_type'] === 'customer') {
                    $this->messagesModel->updateMessage($con['id']);
                }
            }

            $this->view('app', [
                'page' => '/conversation/detail',
                'title' => 'Thông tin hội thoại',
                'conversations' => $conversations
            ]);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: ' . URL_APP . '/conversation');
            exit;
        }
    }

    public function getConversationByCustomerId($id)
    {
        $conversations = $this->conversationModel->getConversationByCustomer($id);
        echo json_encode([
            'status' => 200,
            'message' => 'Get theo customer thành công',
            'data' => $conversations
        ]);
    }

    public function getConversationByConversation($id)
    {
        $conversations = $this->conversationModel->getConversation($id);
        echo json_encode([
            'status' => 200,
            'message' => 'Get theo conversation thành công',
            'data' => $conversations
        ]);
    }

    public function getDetailMessage($id)
    {
        $conversations = $this->conversationModel->getConversation($id);
        echo json_encode([
            'status' => 200,
            'message' => 'Get thành công',
            'data' => $conversations
        ]);
    }

    public function updateMessageStatus($id)
    {
        $conversations = $this->conversationModel->getConversation($id);
        foreach ($conversations as $con) {
            $this->messagesModel->updateMessage($con['id']);
        }
        echo json_encode([
            'status' => 200,
            'message' => 'Get thành công',
            'data' => $conversations
        ]);
    }

    public function store()
    {
        try {
            $conversationId = $this->conversationModel->createConversationNullCustomer();
            if (!$conversationId) {
                throw new Exception("Failed to create conversation");
            }
            
            $conversation = $this->conversationModel->getConversation($conversationId);
            echo json_encode([
                'status' => 200,
                'message' => 'Tạo thành công',
                'data' => ['conversation' => $conversation]
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function storeConversationByCustomer($id)
    {
        try {
            $existingConversation = $this->conversationModel->getConversationByCustomer($id);
            
            if (!empty($existingConversation)) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Đã có conversation',
                    'data' => ['conversation_id' => $existingConversation[0]['conversation_id']]
                ]);
                return;
            }

            $conversationId = $this->conversationModel->createConversationWithCustomer($id);
            if (!$conversationId) {
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
    
    public function createMessageByAdmin()
    {
        try {
            $adminId = $_SESSION['auth_admin']['user_id'];
    
            error_log("Admin ID from session: " . $adminId);
            
            $data = [
                'conversation_id' => $_POST['conversation_id'],
                'content' => $_POST['content'],
                'sender_type' => 'admin',
                'receiver_type' => 'customer',
                'created_by' => $adminId
            ];
            
            error_log("Sending data to model: " . print_r($data, true));
            
            $createdMsg = $this->messagesModel->createMessage($data);
            
            if ($createdMsg === null) {
                throw new Exception('Failed to create message');
            }
            
            echo json_encode([
                'status' => 200,
                'message' => 'Tạo thành công',
                'data' => $createdMsg
            ]);
        } catch (\Throwable $th) {
            error_log("Error in createMessageByAdmin: " . $th->getMessage());
            echo json_encode([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function createMessageByCustomer()
    {
        try {
            if (!isset($_POST['customer_id'])) {
                throw new Exception('Customer ID is required');
            }
    
            $data = [
                'conversation_id' => $_POST['conversation_id'],
                'content' => $_POST['content'],
                'sender_type' => 'customer',
                'receiver_type' => 'admin',
                'sender_id' => $_POST['customer_id'],
                'receiver_id' => isset($_POST['admin_id']) ? $_POST['admin_id'] : null,
                'created_by' => $_POST['customer_id'] 
            ];
            
            $createdMsg = $this->messagesModel->createMessage($data);
            if (!$createdMsg) {
                throw new Exception('Không thể lưu tin nhắn');
            }
    
            echo json_encode([
                'status' => 200,
                'message' => 'Tạo thành công',
                'data' => $createdMsg
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function deleteMessage($id)
    {
        $this->messagesModel->deleteMessage($id);
        echo json_encode([
            'status' => 204,
            'message' => 'Đã xóa tin nhắn',
        ]);
    }
}