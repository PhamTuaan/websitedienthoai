<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

<style>
    .chatbot-toggler {
        position: fixed;
        bottom: 30px;
        right: 35px;
        outline: none;
        border: none;
        height: 50px;
        width: 50px;
        display: flex;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #724ae8;
        transition: all 0.2s ease;
        z-index: 10000;
    }

    body.show-chatbot .chatbot-toggler {
        transform: rotate(90deg);
    }

    .chatbot-notification {
        position: absolute;
        top: -5px;
        right: -5px;
        display: none;
    }

    .chatbot-toggler span.material-symbols-rounded,
    .chatbot-toggler span.material-symbols-outlined {
        position: absolute;
        color: #fff;
    }

    .chatbot-toggler span.material-symbols-outlined {
        display: none;
    }

    body.show-chatbot .chatbot-toggler span.material-symbols-rounded {
        display: none;
    }

    body.show-chatbot .chatbot-toggler span.material-symbols-outlined {
        display: block;
    }

    .chatbot {
        position: fixed;
        right: 35px;
        bottom: 90px;
        width: 420px;
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
        transform: scale(0.5);
        transform-origin: bottom right;
        box-shadow: 0 0 128px 0 rgba(0,0,0,0.1),
                    0 32px 64px -48px rgba(0,0,0,0.5);
        transition: all 0.2s ease;
        z-index: 10000;
    }

    body.show-chatbot .chatbot {
        opacity: 1;
        pointer-events: auto;
        transform: scale(1);
    }

    .chatbot header {
        padding: 16px 0;
        text-align: center;
        background: #724ae8;
        position: relative;
        z-index: 2;
    }

    .chatbot header h2 {
        color: #fff;
        font-size: 1.4rem;
        margin-bottom: 2px;
    }

    .chatbot header .chat-mode-toggle {
        color: #fff;
        margin: 8px 0;
    }

    .chatbot .close-btn {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        cursor: pointer;
    }

    .chat-wrapper {
        position: relative;
        height: 510px;
        overflow: hidden;
    }

    .chat-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transition: transform 0.3s ease-in-out;
        padding: 30px 20px 100px;
        overflow-y: auto;
    }

    .bot-chat {
        transform: translateX(0);
    }

    .admin-chat {
        transform: translateX(100%);
        background: #fff;
    }

    body.admin-mode .bot-chat {
        transform: translateX(-100%);
    }

    body.admin-mode .admin-chat {
        transform: translateX(0);
    }

    .chat-input {
        position: absolute;
        bottom: 0;
        width: 100%;
        display: flex;
        gap: 5px;
        background: #fff;
        padding: 5px 20px;
        border-top: 1px solid #ddd;
    }

    .chat-input textarea {
        height: 55px;
        width: 100%;
        border: none;
        outline: none;
        resize: none;
        padding: 15px 15px 15px 0;
        font-size: 0.95rem;
    }

    .chat-input span {
        align-self: flex-end;
        color: #724ae8;
        cursor: pointer;
        height: 55px;
        display: flex;
        align-items: center;
        font-size: 1.35rem;
    }

    .chat {
        display: flex;
        list-style: none;
        margin: 15px 0;
    }

    .outgoing {
        justify-content: flex-end;
    }

    .incoming .chat-icon {
        width: 32px;
        height: 32px;
        color: #fff;
        cursor: default;
        text-align: center;
        line-height: 32px;
        align-self: flex-end;
        background: #724ae8;
        border-radius: 4px;
        margin: 0 10px 7px 0;
    }

    .chat p {
        white-space: pre-wrap;
        padding: 12px 16px;
        border-radius: 10px 10px 0 10px;
        max-width: 75%;
        color: #fff;
        font-size: 0.95rem;
        background: #724ae8;
    }

    .incoming p {
        border-radius: 10px 10px 10px 0;
        color: #000;
        background: #f2f2f2;
    }

    .chat.error p {
        color: #721c24;
        background: #f8d7da;
    }

    @media (max-width: 490px) {
        .chatbot {
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            border-radius: 0;
        }

        .chat-wrapper {
            height: calc(100% - 120px);
        }

        .chatbot-toggler {
            right: 20px;
            bottom: 20px;
        }
    }
</style>

<input type="hidden" id="cb_customer_id" 
    value="<?php echo isset($_SESSION['auth']['customer_id']) ? $_SESSION['auth']['customer_id'] : 'noconversation'; ?>">

<button class="chatbot-toggler">
    <span class="badge rounded-pill bg-danger p-2" id="chatbox-notification">
        <i class="fa fa-bell" aria-hidden="true"></i>
    </span>
    <span class="material-symbols-rounded">mode_comment</span>
    <span class="material-symbols-outlined">close</span>
</button>

<div class="chatbot">
    <header>
        <h2>HỖ TRỢ KHÁCH HÀNG</h2>
        <span class="close-btn material-symbols-outlined">close</span>
        <div class="chat-mode-toggle form-check">
            <input class="form-check-input" type="checkbox" id="chat-mode-toggle">
            <label class="form-check-label" for="chat-mode-toggle">
                Chat với nhân viên hỗ trợ
            </label>
        </div>
    </header>
    <div class="chat-wrapper">
        <div class="chat-container bot-chat"></div>
        <div class="chat-container admin-chat"></div>
    </div>
    <div class="chat-input">
        <textarea placeholder="Nhập tin nhắn..." required></textarea>
        <span class="material-symbols-rounded">send</span>
    </div>
</div>

 <!-- Mục đích: Khởi tạo cấu hình và trạng thái của ứng dụng.
    config: Cấu hình bao gồm URL cơ sở API, mã thông báo API và thời gian chờ giữa các lần lấy tin nhắn.
    elements: Lưu trữ các phần tử DOM như nút bật/tắt, vùng chat, và các ô nhập liệu.
    state: Lưu trữ trạng thái của chatbot, ví dụ như chế độ (admin hay bot), ID cuộc trò chuyện và các trạng thái khác. -->
<script>
const chatApp = {
    config: {
        baseUrl: '/phone-ecommerce-chat/customer',
        apiToken: 'VNMZZ7NOD2XZCPRYHPYFREFTPPFMMBCT',
        pollInterval: 5000
    },

    elements: {
        toggler: document.querySelector('.chatbot-toggler'),
        closeBtn: document.querySelector('.close-btn'),
        botChat: document.querySelector('.bot-chat'),
        adminChat: document.querySelector('.admin-chat'),
        input: document.querySelector('.chat-input textarea'),
        sendBtn: document.querySelector('.chat-input span'),
        modeToggle: document.getElementById('chat-mode-toggle'),
        notification: document.getElementById('chatbox-notification'),
        customerId: document.getElementById('cb_customer_id')
    },

    state: {
        isAdminMode: false,
        conversationId: null,
        inputInitHeight: null,
        isBotReady: true,
        pollInterval: null
    },

    // Mục đích: Gán các sự kiện cho các phần tử trong ứng dụng.
    // attachEventListeners: Gán các sự kiện như thay đổi chế độ chat, gửi tin nhắn và điều khiển mở/đóng chatbot.
    init() {
        this.state.inputInitHeight = this.elements.input.scrollHeight;
        this.attachEventListeners();
        this.showWelcomeMessage();
    },

 // attachEventListeners: Gán các sự kiện như thay đổi chế độ chat, gửi tin nhắn và điều khiển mở/đóng chatbot.
    attachEventListeners() {
        this.elements.modeToggle.addEventListener('change', () => {
            this.toggleChatMode();
        });

        this.elements.input.addEventListener('input', () => this.adjustInputHeight());
        this.elements.input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey && window.innerWidth > 800) {
                e.preventDefault();
                this.handleMessageSend();
            }
        });

        this.elements.sendBtn.addEventListener('click', () => this.handleMessageSend());
        this.elements.toggler.addEventListener('click', () => this.toggleChat());
        this.elements.closeBtn.addEventListener('click', () => this.closeChat());
    },

    toggleChatMode() {
        const previousMode = this.state.isAdminMode;
        this.state.isAdminMode = this.elements.modeToggle.checked;
        document.body.classList.toggle('admin-mode', this.state.isAdminMode);
        
        if (this.state.isAdminMode) {
            this.elements.adminChat.innerHTML = '';
            this.addMessageToChat(
                "Xin chào! Bạn đang chat với nhân viên hỗ trợ. Vui lòng chờ phản hồi.",
                'incoming'
            );
            
            setTimeout(() => {
                this.loadExistingConversation();
            }, 100);
            
            this.startMessagePolling();
        } else {
            this.stopMessagePolling();
            if (!this.elements.botChat.hasChildNodes()) {
                this.showWelcomeMessage(false);
            }
        }
    },

    showWelcomeMessage(isAdmin = false) {
        const container = isAdmin ? this.elements.adminChat : this.elements.botChat;
        const message = isAdmin
            ? "Xin chào! Bạn đang chat với nhân viên hỗ trợ. Vui lòng chờ phản hồi."
            : 'Xin chào! 👋\nBạn có thể hỏi tôi về sản phẩm, giá cả hoặc chính sách của cửa hàng.';

        container.innerHTML = '';
        this.addMessageToChat(message, 'incoming');
    },

    addMessageToChat(message, type, isError = false) {
        const container = this.state.isAdminMode ? this.elements.adminChat : this.elements.botChat;
        
        const li = document.createElement('li');
        li.classList.add('chat', type, 'message-fade-in');
        if (isError) li.classList.add('error');

        let content = type === 'incoming'
            ? `<span class="chat-icon material-symbols-outlined">smart_toy</span><p>${message}</p>`
            : `<p>${message}</p>`;

        li.innerHTML = content;
        container.appendChild(li);
        this.scrollToBottom(container);
    },

    // Xử lý tin nhắn gửi đi
    // Lấy tin nhắn từ ô nhập liệu, gửi tin nhắn và quyết định gửi tin nhắn tới bot hoặc nhân viên hỗ trợ tùy thuộc vào chế độ.
    async handleMessageSend() {
        const message = this.elements.input.value.trim();
        if (!message) return;

        this.elements.input.value = '';
        this.adjustInputHeight();

        this.addMessageToChat(message, 'outgoing');

        if (this.state.isAdminMode) {
            await this.sendToAdmin(message);
        } else {
            if (this.state.isBotReady) {
                await this.sendToBot(message);
            }
        }
    },

    // async sendToBot(message) {
    //     this.state.isBotReady = false;
    //     try {
    //         const response = await fetch(
    //             `https://api.wit.ai/message?v=20240522&q=${encodeURIComponent(message)}`,
    //             {
    //                 headers: {
    //                     "Authorization": `Bearer ${this.config.apiToken}`,
    //                 }
    //             }
    //         );

    //         const data = await response.json();
    //         let responseMessage;

    //         const firstEntityKey = Object.keys(data.entities)[0];
    //         const firstEntity = data.entities[firstEntityKey]?.[0];
    //         const entityValue = firstEntity?.value;

    //         if (entityValue === message) {
    //             responseMessage = "Xin lỗi, Tôi không hiểu bạn cần gì, Tôi sẽ chuyển tin nhắn của bạn đến cho Quản trị viên. Vui lòng trở lại trang web sau khoảng 1h để nhận thông báo mới. Bạn có thể nhập trợ giúp để tìm hiểu các lệnh";
    //         } else if (entityValue) {
    //             responseMessage = entityValue;
    //         } else {
    //             responseMessage = "Xin lỗi, tôi không hiểu. Hãy thử nhập 'help' để xem hướng dẫn.";
    //         }

    //         await this.createConversation();
    //         await this.sendMessage(message);
            
    //         const formData = new FormData();
    //         formData.append('conversation_id', this.state.conversationId);
    //         formData.append('content', responseMessage);
    //         formData.append('customer_id', this.elements.customerId.value);

    //         await fetch(`${this.config.baseUrl}/conversation/createMessageByAdmin`, {
    //             method: 'POST',
    //             body: formData
    //         });

    //         this.addMessageToChat(responseMessage, "incoming");

    //     } catch (error) {
    //         console.error('Error:', error);
    //         const errorMsg = "Xin lỗi, Tôi không hiểu bạn cần gì, Tôi sẽ chuyển tin nhắn của bạn đến cho Quản trị viên. Vui lòng trở lại trang web sau khoảng 1h để nhận thông báo mới. Bạn có thể nhập trợ giúp để tìm hiểu các lệnh";
    //         this.addMessageToChat(errorMsg, "incoming", true);
            
    //         const formData = new FormData();
    //         formData.append('conversation_id', this.state.conversationId);
    //         formData.append('content', errorMsg);
    //         formData.append('customer_id', this.elements.customerId.value);

    //         await fetch(`${this.config.baseUrl}/conversation/createMessageByAdmin`, {
    //             method: 'POST',
    //             body: formData
    //         });
    //     } finally {
    //         this.state.isBotReady = true;
    //     }
    // },

    // Gửi tin nhắn tới Bot
    // Gửi yêu cầu đến API của Wit.ai để xử lý và nhận lại phản hồi.
    async sendToBot(message) {
        this.state.isBotReady = false;
        try {
            const response = await fetch(
                `https://api.wit.ai/message?v=20241212&q=${encodeURIComponent(message)}`,
                {
                    headers: {
                        "Authorization": `Bearer ${this.config.apiToken}`,
                    }
                }
            );

            const data = await response.json();
            let responseMessage;

            const firstEntityKey = Object.keys(data.entities)[0];
            const firstEntity = data.entities[firstEntityKey]?.[0];
            const entityValue = firstEntity?.value;

            if (entityValue === message) {
                responseMessage = "Xin lỗi, tôi không hiểu bạn cần gì, bạn có thể nhấn nút phía trên để chat với nhân viên và được hỗ trợ chi tiết hơn.";
            } else if (entityValue) {
                responseMessage = entityValue;
            } else {
                responseMessage = "Xin lỗi, tôi không hiểu bạn cần gì, bạn có thể nhấn nút phía trên để chat với nhân viên và được hỗ trợ chi tiết hơn.";
            }

            this.addMessageToChat(responseMessage, "incoming");

        } catch (error) {
            console.error('Error:', error);
            const errorMsg = "Xin lỗi, tôi không hiểu bạn cần gì, bạn có thể nhấn nút phía trên để chat với nhân viên và được hỗ trợ chi tiết hơn.";
            this.addMessageToChat(errorMsg, "incoming", true);
        } finally {
            this.state.isBotReady = true;
        }
    },

    // Gửi tin nhắn tới nhân viên hỗ trợ (admin).
    // Nếu chưa có conversationId (mã cuộc trò chuyện), hàm sẽ tạo một cuộc trò chuyện mới bằng cách gọi hàm createConversation.
    // Sau đó, hàm sẽ gọi sendMessage để gửi tin nhắn đến nhân viên hỗ trợ.
    async sendToAdmin(message) {
        try {
            if (!this.state.conversationId) {
                await this.createConversation();
            }
            await this.sendMessage(message);
        } catch (error) {
            console.error('Error sending to admin:', error);
            this.addMessageToChat(
                'Anh/Chị vui lòng đăng nhập vào hệ thống để được tư vấn trực tiếp.', 
                'incoming',
                true
            );
        }
    },

    // Tạo một cuộc trò chuyện mới giữa khách hàng và nhân viên hỗ trợ.
    // Gửi một yêu cầu POST đến API để tạo cuộc trò chuyện mới.
    // Nếu thành công, conversationId (ID của cuộc trò chuyện) sẽ được lưu vào state và trả về dữ liệu cuộc trò chuyện.    
    async createConversation() {
        try {
            const response = await fetch(
                `${this.config.baseUrl}/conversation/storeConversationByCustomer/${this.elements.customerId.value}`,
                {
                    method: 'POST',
                    body: new FormData()
                }
            );

            const data = await response.json();
            if (data.status === 200) {
                this.state.conversationId = data.data.conversation_id;
                return data.data;
            }
            throw new Error(data.message || 'Failed to create conversation');
        } catch (error) {
            console.error('Error creating conversation:', error);
            throw error;
        }
    },

    // Gửi tin nhắn của khách hàng đến hệ thống.
    // Kiểm tra nếu không có conversationId, hàm sẽ trả về false.
    // Dữ liệu cuộc trò chuyện, tin nhắn và ID khách hàng sẽ được đóng gói trong FormData và gửi đến API để tạo tin nhắn mới.
    // Nếu phản hồi từ API cho biết thành công (mã trạng thái là 200), hàm trả về true, nếu không, trả về false.   
    async sendMessage(message) {
        if (!this.state.conversationId) return false;

        const formData = new FormData();
        formData.append('conversation_id', this.state.conversationId);
        formData.append('content', message);
        formData.append('customer_id', this.elements.customerId.value);

        try {
            const response = await fetch(
                `${this.config.baseUrl}/conversation/createMessageByCustomer`,
                {
                    method: 'POST',
                    body: formData
                }
            );
            
            const data = await response.json();
            return data.status === 200;
        } catch (error) {
            console.error('Error sending message:', error);
            return false;
        }
    },

    // async loadExistingConversation() {
    //     if (this.elements.customerId.value === 'noconversation') return;

    //     try {
    //         const response = await fetch(
    //             `${this.config.baseUrl}/conversation/getConversationByCustomerId/${this.elements.customerId.value}`
    //         );
    //         const data = await response.json();
            
    //         if (data.status === 200 && data.data.length > 0) {
    //             this.state.conversationId = data.data[0].conversation_id;
    //             this.startMessagePolling();
    //         }
    //     } catch (error) {
    //         console.error('Error loading conversation:', error);
    //         this.addMessageToChat(
    //             "Không thể tải tin nhắn. Vui lòng thử lại sau.",
    //             'incoming',
    //             true
    //         );
    //     }
    // },

    // Tải cuộc trò chuyện hiện tại của khách hàng nếu có
    // Gửi yêu cầu GET đến API để lấy cuộc trò chuyện của khách hàng dựa trên customerId.
    // Nếu cuộc trò chuyện tồn tại và trả về thành công, ID cuộc trò chuyện sẽ được lưu và các
    // tin nhắn sẽ được tải và hiển thị trên giao diện chat với hiệu ứng mượt mà (delay 50ms giữa các tin nhắn).
    async loadExistingConversation() {
    if (this.elements.customerId.value === 'noconversation') return;

    try {
        const response = await fetch(
            `${this.config.baseUrl}/conversation/getConversationByCustomerId/${this.elements.customerId.value}`
        );
        const data = await response.json();
        
        if (data.status === 200 && data.data.length > 0) {
            this.state.conversationId = data.data[0].conversation_id;
            
            // Load message with smooth animation
            const messages = data.data;
            this.elements.adminChat.innerHTML = '';
            
            // Add each message with a small delay to create a smooth effect
            messages.forEach((message, index) => {
                setTimeout(() => {
                    this.addMessageToChat(
                        message.content,
                        message.sender_type === 'customer' ? 'outgoing' : 'incoming',
                        false
                    );
                }, index * 50); // 50ms delay between each message
            });
        }
    } catch (error) {
        console.error('Error loading conversation:', error);
        this.addMessageToChat(
            "Không thể tải tin nhắn. Vui lòng thử lại sau.",
            'incoming',
            true
        );
    }
},

// Bắt Đầu Lấy Tin Nhắn Mới 
    startMessagePolling() {
        if (!this.state.pollInterval) {
            this.state.pollInterval = setInterval(() => this.pollMessages(), this.config.pollInterval);
        }
    },
// Dừng Lấy Tin Nhắn Mới
    stopMessagePolling() {
        if (this.state.pollInterval) {
            clearInterval(this.state.pollInterval);
            this.state.pollInterval = null;
        }
    },

    async pollMessages() {
        if (!this.state.conversationId || !this.state.isAdminMode) return;

        try {
            const response = await fetch(
                `${this.config.baseUrl}/conversation/getDetailMessage/${this.state.conversationId}`
            );
            const data = await response.json();
            
            if (data.status === 200 && data.data) {
                this.updateChatDisplay(data.data);
                this.updateNotificationStatus(data.data);
            }
        } catch (error) {
            console.error('Error polling messages:', error);
        }
    },

    // Cập nhật hiển thị chat khi có tin nhắn mới.
    updateChatDisplay(messages) {
        if (!this.state.isAdminMode) return;
        
        if (this.elements.adminChat.children.length !== messages.length) {
            this.elements.adminChat.innerHTML = '';
            messages.forEach(message => {
                this.addMessageToChat(
                    message.content,
                    message.sender_type === 'customer' ? 'outgoing' : 'incoming'
                );
            });
        }
    },

    updateNotificationStatus(messages) {
        const hasUnread = messages.some(msg => 
            !msg.read_at && msg.sender_type !== 'customer'
        );
        this.elements.notification.style.display = hasUnread ? 'block' : 'none';
    },

    async markMessagesAsRead() {
        if (!this.state.conversationId) return;

        try {
            const formData = new FormData();
            formData.append('conversation_id', this.state.conversationId);

            await fetch(
                `${this.config.baseUrl}/conversation/updateMessageStatus/${this.state.conversationId}`,
                {
                    method: 'POST',
                    body: formData
                }
            );

            this.elements.notification.style.display = 'none';
        } catch (error) {
            console.error('Error marking messages as read:', error);
        }
    },

    toggleChat() {
        document.body.classList.toggle('show-chatbot');
        if (document.body.classList.contains('show-chatbot')) {
            this.markMessagesAsRead();
            if (this.state.isAdminMode) {
                this.startMessagePolling();
            }
        } else {
            this.stopMessagePolling();
        }
    },

    closeChat() {
        document.body.classList.remove('show-chatbot');
        this.stopMessagePolling();
    },

    scrollToBottom(container) {
        container.scrollTo({
            top: container.scrollHeight,
            behavior: 'smooth'
        });
    },

    adjustInputHeight() {
        this.elements.input.style.height = `${this.state.inputInitHeight}px`;
        this.elements.input.style.height = `${this.elements.input.scrollHeight}px`;
    }
};
const newStyles = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-fade-in {
        animation: fadeIn 0.3s ease forwards;
    }

    .chat-container {
        transition: opacity 0.3s ease;
    }

    .admin-chat {
        opacity: 0;
    }

    body.admin-mode .admin-chat {
        opacity: 1;
    }

    body.admin-mode .bot-chat {
        opacity: 0;
    }
`;

const styleSheet = document.createElement("style");
styleSheet.textContent = newStyles;
document.head.appendChild(styleSheet);


document.addEventListener('DOMContentLoaded', () => {
    chatApp.init();
});
</script>