<!-- Breadcrumb Begin -->
<div class="breadcrumb-option pt-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="/"><i class="fa fa-home"></i> Trang chủ</a>
                    <span>Lịch sử đặt hàng</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Order History Section Begin -->
<section class="container mb-5">
    <div class="row" id="orderHistoryContainer">
        <!-- Dữ liệu sẽ được render bởi JavaScript -->
    </div>
</section>
<!-- Order History Section End -->

<script>
const IMAGES_PATH = "http://localhost/phone-ecommerce-chat/storages/public";
// Thêm hàm helper để xử lý ảnh
const getImageUrl = (imagePath) => {
    if (!imagePath) return 'path/to/default/image.jpg';
    return `${IMAGES_PATH}/${imagePath}`;
};

// Format tiền VND
const formatPrice = (price) => Number(price).toLocaleString('vi-VN') + ' VND';

// Format ngày giờ
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

// Lấy icon và text theo trạng thái
const getStatusIconAndText = (status) => {
    switch (status) {
        case 'đang chờ':
            return `<i class="fa fa-clock text-warning me-2"></i>
                    <span class="text-warning">Đơn hàng đang chờ xác nhận</span>`;
        case 'đang giao':
            return `<i class="fa fa-truck text-primary me-2"></i>
                    <span class="text-primary">Đơn hàng đang được vận chuyển</span>`;
        case 'đã giao':
            return `<i class="fa fa-check-circle text-success me-2"></i>
                    <span class="text-success">Đơn hàng đã giao thành công</span>`;
        case 'đã hủy':
            return `<i class="fa fa-times-circle text-danger me-2"></i>
                    <span class="text-danger">Đơn hàng đã bị hủy</span>`;
        default:
            return `<i class="fa fa-clock text-warning me-2"></i>
                    <span class="text-warning">Đơn hàng đang chờ xác nhận</span>`;
    }
};

// Lấy nút điều khiển theo trạng thái
const getButtonBasedOnStatus = (status, orderId) => {
    switch (status) {
        case 'đang chờ':
            return `<button class="site-btn rounded w-100" 
                        onclick="cancelOrder('${orderId}')">
                        Hủy đơn hàng
                    </button>`;
        case 'đang giao':
            return `<button class="btn btn-success w-100" 
                        onclick="confirmOrder('${orderId}')">
                        Xác nhận đã nhận được hàng
                    </button>`;
        case 'đã giao':
            return `<button class="btn btn-secondary w-100" disabled>
                        Đã giao thành công
                    </button>`;
        default:
            return `<button class="btn btn-secondary w-100" disabled>
                        Đã hủy
                    </button>`;
    }
};

// Render đơn hàng
const renderOrderHistory = (orderHistory) => {
    const container = document.getElementById('orderHistoryContainer');
    console.log("Order history data:", orderHistory); // Thêm log để debug
    
    if (!orderHistory || orderHistory.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">Bạn chưa có đơn hàng nào</h4>
            </div>`;
        return;
    }

    const html = orderHistory.map(order => `
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold">
                            Đơn hàng #${order.order_id}
                        </h6>
                        <div>
                            ${getStatusIconAndText(order.status)}
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    ${renderOrderProducts(order.orderDetail)}
                    
                    <hr class="my-3">
                    
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="mb-0 text-muted">
                                Ngày đặt: ${formatDate(order.created_at)}
                            </p>
                            <p class="mb-0 text-muted">
                                Người nhận: ${order.name_receiver}
                            </p>
                            <p class="mb-0 text-muted">
                                Địa chỉ: ${order.address_receiver}
                            </p>
                            ${order.promotion ? `
                                <p class="mb-0 text-success">
                                    Mã giảm giá: ${order.promotion.promotion_code} (-${order.promotion.value}%)
                                </p>
                            ` : ''}
                        </div>
                        <div class="col-auto text-right">
                            <h5 class="mb-3 text-danger">
                                ${formatPrice(order.total_price)}
                            </h5>
                            ${getButtonBasedOnStatus(order.status, order.order_id)}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    container.innerHTML = html;
};

// Render sản phẩm trong đơn hàng
const renderOrderProducts = (products) => {
    if (!products || products.length === 0) return '';
    
    return products.map(item => `
        <div class="row mb-3">
            <div class="col-2 col-md-1">
                <img src="${IMAGES_PATH}/${item.product.image}" 
                    alt="${item.product.product_name}"
                    class="img-fluid rounded">
            </div>
            <div class="col">
                <h6 class="mb-1">${item.product.product_name}</h6>
                <p class="mb-0 text-muted">
                    Số lượng: ${item.quantity} x ${formatPrice(item.product.price)}
                </p>
            </div>
            <div class="col-auto">
                <h6 class="mb-0 text-danger">
                    ${formatPrice(item.quantity * item.product.price)}
                </h6>
            </div>
        </div>
    `).join('');
};

// Xử lý xác nhận đơn hàng
const confirmOrder = (orderId) => {
    if (confirm('Xác nhận đã nhận được đơn hàng?')) {
        $.ajax({
            url: `http://localhost/phone-ecommerce-chat/customer/user/receiveOrder/${orderId}`,
            method: 'GET',
            success: function(res) {
                if (res.status === 200) {
                    showToast('Xác nhận đơn hàng thành công', true);
                    window.location.reload();
                }
            },
            error: function() {
                showToast('Xác nhận đơn hàng thất bại', false);
            }
        });
    }
};

// Xử lý hủy đơn hàng  
const cancelOrder = (orderId) => {
    if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        $.ajax({
            url: `http://localhost/phone-ecommerce-chat/customer/user/cancleOrder/${orderId}`,
            method: 'GET',
            success: function(res) {
                if (res.status === 200) {
                    showToast('Hủy đơn hàng thành công', true);
                    window.location.reload();
                }
            },
            error: function() {
                showToast('Hủy đơn hàng thất bại', false);
            }
        });
    }
};

// Khởi tạo
// Khởi tạo
$(document).ready(function() {
    $.ajax({
        url: `http://localhost/phone-ecommerce-chat/customer/user/getOrderHistory`,
        method: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 200) {
                renderOrderHistory(res.data);
            } else if (res.status === 401) {
                // Redirect to login page
                window.location.href = 'http://localhost/phone-ecommerce-chat/customer/auth/login';
            } else {
                showToast(res.message || 'Không thể tải lịch sử đơn hàng', false);
            }
        },
        error: function(xhr) {
            if (xhr.status === 401) {
                // Redirect to login page
                window.location.href = 'http://localhost/phone-ecommerce-chat/customer/auth/login';
            } else {
                showToast('Không thể tải lịch sử đơn hàng', false);
            }
        }
    });
});
</script>