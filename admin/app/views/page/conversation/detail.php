<div class="card mb-4 mx-4">
    <?php if (empty($conversations)) : ?>
        <div class="alert alert-warning m-3" role="alert">
            Không tìm thấy cuộc hội thoại
        </div>
    <?php else : ?>
        <input type="hidden" value="<?php echo $conversations[0]['conversation_id']; ?>" id="conversation_id">

        <div class="card-header pb-0">
            <div class="d-flex justify-content-start gap-3">
                <a href="<?php echo URL_APP . '/conversation'; ?>" class="mb-0 d-flex align-items-center" type="button">
                    <i class="fas fa-arrow-left fs-6"></i>
                </a>
                <div>
                    <h5 class="mb-0 fw-bolder text-dark fs-5">
                        Khách hàng "<?php echo isset($conversations[0]['customer_name']) ? $conversations[0]['customer_name'] : 'Ẩn danh'; ?>"
                    </h5>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="m-3 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                <span class="alert-text text-white">
                    <?php echo $_SESSION['success']; ?>
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="m-3 alert alert-danger alert-dismissible fade show" id="alert-error" role="alert">
                <span class="alert-text text-white">
                    <?php echo $_SESSION['error']; ?>
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card-body px-0 pt-0 pb-2">
            <div class="scroll-area card shadow-none border-1 rounded mx-4 my-2 p-2" id="messageContainer">
                <!-- Messages will be loaded here -->
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between gap-3">
                <input type="text" class="form-control" placeholder="Nhập tin nhắn..." name="content" id="msg_content">
                <button type="button" class="btn bg-gradient-primary btn-md d-flex align-items-center gap-2" id="sendMessage">
                    Gửi <i class="far fa-paper-plane"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.scroll-area {
    height: 500px;
    overflow-y: auto;
    background: #fbfbfb;
    scroll-behavior: smooth;
}

.message-time {
    font-size: 0.75rem;
    color: #6c757d;
}

.customer-message, .admin-message {
    max-width: 75%;
    word-wrap: break-word;
}

.message-content {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
}

.cursor-pointer {
    cursor: pointer;
}
</style>

<script>
const URL = "http://localhost/phone-ecommerce-chat/admin"
const conversationId = document.getElementById('conversation_id')?.value;

// Fetch messages
const fetchConversation = async () => {
    try {
        if (!conversationId) {
            console.error('conversation_id không tồn tại');
            return;
        }

        const response = await fetch(`${URL}/conversation/getDetailMessage/${conversationId}`);
        const data = await response.json();
        
        console.log('API Response:', data);
        
        if (data.status === 200) {
            console.log('Messages:', data.data);
            renderMessages(data.data);
        } else {
            console.error(data.message);
        }
    } catch (error) {
        console.error('Lỗi khi tải tin nhắn:', error);
    }
}

// Update message status
const updateMessageStatus = async () => {
    try {
        if (!conversationId) return;

        const response = await fetch(`${URL}/conversation/updateMessageStatus/${conversationId}`);
        const data = await response.json();
        
        if (data.status !== 200) {
            console.error('Cập nhật trạng thái thất bại:', data.message);
        }
    } catch (error) {
        console.error('Lỗi khi cập nhật trạng thái:', error);
    }
}

// Delete message
const deleteMessage = async (messageId) => {
    try {
        if (!confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')) return;

        const response = await fetch(`${URL}/conversation/deleteMessage/${messageId}`);
        const data = await response.json();
        
        if (data.status === 204) {
            showToast('Đã xóa tin nhắn', true);
            await fetchConversation();
        } else {
            showToast('Xóa tin nhắn không thành công', false);
        }
    } catch (error) {
        console.error('Lỗi khi xóa tin nhắn:', error);
        showToast('Có lỗi khi xóa tin nhắn', false);
    }
}

// Render messages
const renderMessages = (messages) => {
    if (!messages?.length) return;

    const messageContainer = document.getElementById('messageContainer');
    const currentScrollHeight = messageContainer.scrollHeight;
    const isScrolledToBottom = messageContainer.scrollTop + messageContainer.clientHeight >= currentScrollHeight - 100;

    const messagesHTML = messages.map((message) => {
        if (!message.content || !message.created_at) return '';
        
        const time = new Date(message.created_at).toLocaleString();
        
        if (message.deleted_by) return '';
        
        if (message.sender_type === "customer") {
            return `
                <div class="d-flex mb-3">
                    <div class="customer-message">
                        <div class="message-content bg-light">
                            <p class="mb-0">${escapeHtml(message.content)}</p>
                        </div>
                        <small class="message-time">${time}</small>
                    </div>
                </div>
            `;
        } else {
            return `
                <div class="d-flex flex-row-reverse mb-3">
                    <div class="admin-message">
                        <div class="message-content bg-primary text-white cursor-pointer" onclick="deleteMessage('${message.id}')">
                            <p class="mb-0">${escapeHtml(message.content)}</p>
                        </div>
                        <small class="message-time text-end d-block">${time}</small>
                    </div>
                </div>
            `;
        }
    }).join('');

    messageContainer.innerHTML = messagesHTML;

    if (isScrolledToBottom) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
}

// Send message
const sendMessage = async () => {
    const content = document.getElementById('msg_content').value.trim();
    
    if (!content || !conversationId) return;

    try {
        const formData = new FormData();
        formData.append('conversation_id', conversationId);
        formData.append('content', content);

        const response = await fetch(`${URL}/conversation/createMessageByAdmin`, {
            method: 'POST',
            body: formData  
        });

        const data = await response.json();
        
        console.log('Server response:', data);
        
        if (data.status === 401) {
            showToast('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại', false);
            setTimeout(() => {
                window.location.href = `${URL}/auth/login`;
            }, 2000);
            return;
        }
        
        if (data.status === 200) {
            document.getElementById('msg_content').value = '';
            await fetchConversation();
        } else {
            showToast(data.message || 'Gửi tin nhắn thất bại', false);
        }
    } catch (error) {
        console.error('Lỗi khi gửi tin nhắn:', error);
        showToast('Có lỗi khi gửi tin nhắn', false);
    }
}

// Helpers
const escapeHtml = (unsafe) => {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    if (!conversationId) return;

    // Initial load
    fetchConversation();
    updateMessageStatus();

    // Setup polling
    setInterval(fetchConversation, 5000);

    // Setup message input
    const msgInput = document.getElementById('msg_content');
    msgInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Setup send button
    document.getElementById('sendMessage').addEventListener('click', sendMessage);
});
</script>