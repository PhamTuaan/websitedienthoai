<?php
// Lớp này quản lý các chức năng liên quan đến hội thoại và tin nhắn,
//  bao gồm hiển thị danh sách, chi tiết hội thoại, tạo mới, cập nhật và xóa tin nhắn
class ConversationController extends BaseController
{
    private $conversationModel;
    private $messagesModel;

    // $conversationModel: Là đối tượng của lớp ConversationModel dùng để thao tác với dữ liệu hội thoại.
    // $messagesModel: Là đối tượng của lớp MessagesModel dùng để thao tác với dữ liệu tin nhắn.
    // Hàm khởi tạo (__construct): Khởi tạo hai model trên để sử dụng các phương thức trong database.
    public function __construct()
    {
        $this->conversationModel = $this->model('ConversationModel');
        $this->messagesModel = $this->model('MessagesModel');
    }

    // Hiển thị danh sách hội thoại
    // Lấy danh sách tất cả các hội thoại từ database thông qua phương thức getConversations() của ConversationModel.
    // Gửi dữ liệu đến view /conversation/index để hiển thị.
    public function index()
    {
        $conversations = $this->conversationModel->getConversations();
        $this->view('app', [
            'page' => '/conversation/index',
            'title' => 'Thông tin hội thoại',
            'conversations' => $conversations
        ]);
    }

    // Xem chi tiết hội thoại
    // Lấy thông tin chi tiết của hội thoại dựa trên $id.
    // Nếu hội thoại không tồn tại, đặt thông báo lỗi vào session và chuyển hướng về trang danh sách.
    // Nếu có tin nhắn từ phía khách hàng (sender_type === 'customer'), đánh dấu trạng thái tin nhắn
    //  là đã xem bằng cách gọi updateMessage($id).
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

    // Lấy hội thoại theo khách hàng
    // Truy vấn hội thoại liên quan đến một khách hàng cụ thể ($id) và trả kết quả dạng JSON.
    public function getConversationByCustomerId($id)
    {
        $conversations = $this->conversationModel->getConversationByCustomer($id);
        echo json_encode([
            'status' => 200,
            'message' => 'Get theo customer thành công',
            'data' => $conversations
        ]);
    }

    // Lấy hội thoại theo mã hội thoại
    // Tương tự phương thức trên nhưng tìm kiếm theo mã hội thoại.
    public function getConversationByConversation($id)
    {
        $conversations = $this->conversationModel->getConversation($id);
        echo json_encode([
            'status' => 200,
            'message' => 'Get theo conversation thành công',
            'data' => $conversations
        ]);
    }

    //  Lấy thông tin chi tiết của một hội thoại dựa trên mã hội thoại ($id).
    // Gọi phương thức getConversation($id) của ConversationModel để lấy thông tin hội thoại từ cơ sở dữ liệu.
    public function getDetailMessage($id)
    {
        $conversations = $this->conversationModel->getConversation($id);
        echo json_encode([
            'status' => 200,
            'message' => 'Get thành công',
            'data' => $conversations
        ]);
    }

    // cập nhật trạng thái của các tin nhắn trong một hội thoại, đồng thời trả về dữ liệu chi tiết của hội thoại.
    // Gọi phương thức getConversation($id) từ ConversationModel để lấy danh sách các tin nhắn trong hội thoại với mã $id.
    // Duyệt qua từng tin nhắn ($con) trong danh sách:
    // Gọi phương thức updateMessage($con['id']) từ MessagesModel để cập nhật trạng thái tin nhắn (ví dụ: đánh dấu là đã đọc).
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

    //  Tạo hội thoại
    // Tạo hội thoại mới không có khách hàng liên kết ban đầu. Trả về hội thoại vừa tạo.
    public function store()
    {
        try {
            $this->conversationModel->createConversationNullCustomer();
            $conversation = $this->conversationModel->getLastestConversation();
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

    // Kiểm tra xem đã có hội thoại tồn tại cho khách hàng với mã $id chưa.
    // Nếu chưa có, tạo một hội thoại mới cho khách hàng.
    // Trả về thông báo trạng thái qua JSON để thông báo kết quả.
    public function storeConversationByCustomer($id)
    {
        try {
            $existingConversation = $this->conversationModel->getConversationByCustomer($id);
            if (empty($existingConversation)) {
                $this->conversationModel->createConversationWithCustomer($id);
                echo json_encode([
                    'status' => 200,
                    'message' => 'Tạo thành công',
                ]);
                return;
            }
            echo json_encode([
                'status' => 200,
                'message' => 'Đã tồn tại',
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    // Tạo tin nhắn bởi admin
    // Tạo tin nhắn từ admin gửi đến khách hàng. Dữ liệu tin nhắn được lấy từ $_POST.
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

    // Tạo một tin nhắn mới từ phía khách hàng (customer).
    // Tin nhắn này được gửi từ khách hàng đến quản trị viên (admin).
    // Trả về thông báo trạng thái và dữ liệu tin nhắn vừa tạo.
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
                'receiver_id' => isset($_POST['admin_id']) ? $_POST['admin_id'] : null
            ];
            
            $createdMsg = $this->messagesModel->createMessage($data);
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

    // Xóa tin nhắn
    // Xóa tin nhắn dựa trên mã $id.
    public function deleteMessage($id)
    {
        $this->messagesModel->deleteMessage($id);
        echo json_encode([
            'status' => 204,
            'message' => 'Đã xóa tin nhắn',
        ]);
    }
}