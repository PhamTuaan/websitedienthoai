<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
$isLoggedIn = isset($_SESSION['auth']);
if (!$isLoggedIn) {
    $_SESSION['redirect_after_login'] = '/phone-ecommerce-chat/customer/cart';
    header('Location: /phone-ecommerce-chat/customer/auth/login');
    exit();
}
?>

<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="/"><i class="fa fa-home"></i> Trang chủ</a>
                    <span>Giỏ hàng</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Shop Cart Section Begin -->
<section class="shop-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shop__cart__table">
                    <table id="cartTable">
                        <thead>
                            <tr>
                                <th>Chọn</th>
                                <th>STT</th>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá thành</th>
                                <th>Tổng tiền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="cart__btn update__btn">
                    <button type="button" class="update-cart-btn">
                        <span class="icon_loading"></span> 
                        <span>Cập nhật giỏ hàng</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 offset-lg-8">
                <div class="cart__total__procced" id="cart__total__procced">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Shop Cart Section End -->

<script>
// Constants
const URL = 'http://localhost/phone-ecommerce-chat/customer/cart';
const IMAGES_URL = 'http://localhost/phone-ecommerce-chat/storages/public';
const BASE_URL = '/phone-ecommerce-chat/customer';
const CHECKOUT_URL = '/phone-ecommerce-chat/customer/checkout';

// Auth state
const isAuthenticated = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

const cartHandler = {
    fetchCart: function() {
        $.ajax({
            url: `${URL}/getAll`,
            method: 'GET',
            dataType: 'json',
            success: (res) => {
                if(res.status === 200) {
                    this.renderCart(res.data);
                }
            },
            error: (error) => {
                showToast('Lỗi khi tải giỏ hàng', false);
            }
        });
    },

    renderCart: function(data) {
        const cartElement = document.getElementById('cartTableBody');
        
        if (!data || data.length === 0) {
            cartElement.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <h4 class="text-dark font-weight-bold">Không có sản phẩm trong giỏ hàng!</h4>
                    </td>
                </tr>
            `;
            this.renderCartTotal(0);
            return;
        }

        const cartHTML = data.map((item, index) => {
            const itemTotal = item.price * item.quantity;
            return `
                <tr data-unit-price="${item.price}" data-product-id="${item.product_id}">
                    <td>
                        <input type="checkbox" class="item_selected" 
                            name="selected_items" 
                            value="${item.product_id}" 
                            style="transform: scale(1.5);">
                    </td>
                    <td>${index + 1}</td>
                    <td class="cart__product__item">
                        <img src="${IMAGES_URL}/${item.image}" 
                            alt="${item.product_name}"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        <div class="cart__product__item__title">
                            <h6>${item.product_name}</h6>
                        </div>
                    </td>
                    <td class="cart__quantity">
                        <div class="pro-qty">
                            <button class="dec qtybtn">-</button>
                            <input type="number" 
                                value="${item.quantity}" 
                                class="cart-item-quantity"
                                data-product-id="${item.product_id}"
                                min="1"
                                max="99">
                            <button class="inc qtybtn">+</button>
                        </div>
                    </td>
                    <td class="cart__price">
                        ${Number(item.price).toLocaleString('vi-VN')} VND
                    </td>
                    <td class="cart__total">
                        ${Number(itemTotal).toLocaleString('vi-VN')} VND
                    </td>
                    <td>
                        <button onclick="cartHandler.deleteItem(${item.product_id})" 
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');

        cartElement.innerHTML = cartHTML;
        this.updateSelectedTotal();
    },

    renderCartTotal: function(total) {
        const totalElement = document.getElementById('cart__total__procced');
        const checkoutButtonDisabled = total <= 0 ? 'disabled' : '';
        
        totalElement.innerHTML = `
            <h6>Tổng quan giỏ hàng</h6>
            <ul>
                <li>Tổng tiền 
                    <span id="totalPrice" data-value="${total}">
                        ${Number(total).toLocaleString('vi-VN')} VND
                    </span>
                </li>
            </ul>
            <a href="javascript:void(0);" onclick="cartHandler.processCheckout()" 
                class="primary-btn w-100 ${checkoutButtonDisabled}">
                Tiến hành thanh toán
            </a>
        `;
    },

    calculateRowTotal: function(row) {
        const quantity = parseInt(row.find('.cart-item-quantity').val());
        const unitPrice = parseFloat(row.data('unit-price'));
        return quantity * unitPrice;
    },

    updateRowTotal: function(row) {
        const total = this.calculateRowTotal(row);
        row.find('.cart__total').text(`${Number(total).toLocaleString('vi-VN')} VND`);
    },

    updateSelectedTotal: function() {
        let total = 0;
        $('.item_selected:checked').each((index, checkbox) => {
            const row = $(checkbox).closest('tr');
            total += this.calculateRowTotal(row);
        });
        this.renderCartTotal(total);
    },

    deleteItem: function(productId) {
        if(confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
            $.ajax({
                url: `${URL}/destroy/${productId}`,
                type: 'DELETE',
                success: (res) => {
                    if (res.status === 204) {
                        showToast('Đã xóa sản phẩm khỏi giỏ hàng', true);
                        this.fetchCart();
                        this.updateSelectedTotal();
                    }
                },
                error: (error) => {
                    showToast('Lỗi khi xóa sản phẩm', false);
                }
            });
        }
    },

    updateQuantity: function() {
        const cartItems = [];
        $('.cart-item-quantity').each(function() {
            cartItems.push({
                product_id: $(this).data('product-id'),
                quantity: $(this).val()
            });
        });

        $.ajax({
            url: `${URL}/update`,
            type: 'POST',
            data: { cartDetails: cartItems },
            success: (res) => {
                if (res.status === 200) {
                    showToast('Cập nhật giỏ hàng thành công', true);
                    this.fetchCart();
                    this.updateSelectedTotal();
                }
            },
            error: (error) => {
                showToast('Lỗi khi cập nhật giỏ hàng', false);
            }
        });
    },

    processCheckout: function() {
        console.log('Xử lý checkout...', isAuthenticated);

        if (!isAuthenticated) {
            console.log('Chưa đăng nhập, chuyển đến trang login...');
            sessionStorage.setItem('redirectAfterLogin', CHECKOUT_URL);
            window.location.href = `${BASE_URL}/auth/login`;
            return;
        }

        const selectedItems = this.getSelectedItems();
        
        if (selectedItems.length === 0) {
            showToast('Vui lòng chọn sản phẩm để thanh toán', false);
            return;
        }

        try {
            localStorage.setItem('cartDetails', JSON.stringify(selectedItems));
            
            // Debug log
            console.log('Chuyển đến trang checkout...');
            console.log('Selected items:', selectedItems);
            
            // Redirect to checkout
            window.location.href = CHECKOUT_URL;
        } catch (error) {
            console.error('Lỗi khi xử lý thanh toán:', error);
            showToast('Có lỗi xảy ra khi xử lý thanh toán', false);
        }
    },

    getSelectedItems: function() {
        return Array.from(document.querySelectorAll('input[name="selected_items"]:checked'))
            .map(checkbox => {
                const row = $(checkbox).closest('tr');
                return {
                    product_id: row.data('product-id'),
                    cartQuantity: parseInt(row.find('.cart-item-quantity').val()),
                    price: parseFloat(row.data('unit-price')),
                    image: row.find('img').attr('src').split('/').pop(),
                    product_name: row.find('.cart__product__item__title h6').text()
                };
            });
    }
};

// Event listeners and initialization
$(document).ready(function() {
    // Debug log
    console.log('Cart page initialized');
    console.log('Authentication status:', isAuthenticated);
    
    // Initialize cart
    cartHandler.fetchCart();

    // Event handlers
    $(document).on('change', '.item_selected', function() {
        cartHandler.updateSelectedTotal();
    });

    $(document).on('change', '.cart-item-quantity', function() {
        const row = $(this).closest('tr');
        cartHandler.updateRowTotal(row);
        cartHandler.updateSelectedTotal();
    });

    $(document).on('click', '.qtybtn', function() {
        const input = $(this).siblings('input');
        let value = parseInt(input.val());
        
        if ($(this).hasClass('inc')) {
            value = Math.min(value + 1, parseInt(input.attr('max')));
        } else {
            value = Math.max(value - 1, parseInt(input.attr('min')));
        }
        
        input.val(value).trigger('change');
    });

    $('.update-cart-btn').click(function() {
        cartHandler.updateQuantity();
    });
});
</script>