<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="/"><i class="fa fa-home"></i> Trang chủ</a>
                    <span>Thanh toán</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Checkout Section Begin -->
<section class="checkout spad">
    <div class="container">
        <div class="row">
            <!-- Form bên trái -->
            <div class="col-lg-7">
                <div class="checkout__form">
                    <h3 class="font-weight-bold mb-5">Thông tin người mua</h3>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="checkout__input">
                                <label>Tên người nhận<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name_receiver" placeholder="Anh/Chị">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="checkout__input">
                                <label>Số điện thoại<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone_receiver">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="checkout__input mb-3">
                                <label>Địa chỉ <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <select class="form-control" id="city" name="city">
                                            <option value="">Chọn tỉnh thành</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <select class="form-control" id="district" name="district">
                                            <option value="">Chọn quận huyện</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <select class="form-control" id="ward" name="ward">
                                            <option value="">Chọn phường xã</option>
                                        </select>
                                    </div>
                                </div>
                                <textarea id="address_detail" name="address_detail" class="form-control mt-3" 
                                    rows="3" placeholder="Số nhà, tên đường"></textarea>
                            </div>
                            <div class="checkout__input">
                                <label>Ghi chú <span class="text-muted">(không bắt buộc)</span></label>
                                <textarea class="form-control" name="notes" rows="3" 
                                    placeholder="Ghi chú về đơn hàng"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary bên phải -->
            <div class="col-lg-5">
                <div class="checkout__order">
                    <h5 class="order__title">Đơn đặt hàng của bạn</h5>
                    <div class="checkout__order__products">
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Sản phẩm</strong>
                            <strong>Giá thành</strong>
                        </div>
                        <div id="productCheckout"></div>
                    </div>

                    <hr>
                    <!-- Phần mã giảm giá -->
                    <div class="promocode-section mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Mã giảm giá:</span>
                            <span id="promotion" class="text-success">Không có</span>
                        </div>
                        <div class="d-flex">
                            <input type="text" class="form-control" id="promotion_add" 
                                placeholder="Nhập mã giảm giá">
                            <button class="btn btn-primary ml-2" id="promotion_submit">
                                <i class="fa fa-check"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tổng tiền -->
                    <div class="checkout__order__total">
                        <div class="d-flex justify-content-between">
                            <span class="font-weight-bold">Tổng tiền:</span>
                            <span id="totalPrice" class="text-danger font-weight-bold"></span>
                        </div>
                    </div>

                    <input type="hidden" name="promotion_id" id="promotion_id">
                    <button type="button" id="submitButton" class="site-btn w-100 mt-4">
                        Đặt hàng
                    </button>
                </div>
            </div>
        </div>

        <!-- Bảng chi tiết sản phẩm -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="text-dark font-weight-bold mb-4">Chi tiết sản phẩm</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Hình ảnh</th>
                                <th>Tên SP</th>
                                <th>Số lượng</th>
                                <th>Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody id="productDetailCheckout"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Checkout Section End -->

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>

<!-- Script địa chỉ -->
<script>
const initAddress = () => {
    const citis = document.getElementById("city");
    const districts = document.getElementById("district");
    const wards = document.getElementById("ward");
    
    axios({
        url: "https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json",
        method: "GET",
        responseType: "application/json",
    }).then(result => {
        renderCity(result.data);
    });

    function renderCity(data) {
        for (const x of data) {
            citis.options[citis.options.length] = new Option(x.Name, x.Id);
        }

        citis.onchange = function() {
            district.length = 1;
            ward.length = 1;
            if(this.value != "") {
                const result = data.filter(n => n.Id === this.value);
                for (const k of result[0].Districts) {
                    district.options[district.options.length] = new Option(k.Name, k.Id);
                }
            }
        };

        district.onchange = function() {
            ward.length = 1;
            const dataCity = data.filter((n) => n.Id === citis.value);
            if (this.value != "") {
                const dataWards = dataCity[0].Districts.filter(n => n.Id === this.value)[0].Wards;
                for (const w of dataWards) {
                    wards.options[wards.options.length] = new Option(w.Name, w.Id);
                }
            }
        };
    }
};
</script>

<!-- Script chính -->
<script>
// Code JavaScript ở file trước
const IMAGES_PATH = "http://localhost/phone-ecommerce-chat/storages/public"

$(document).ready(function () {
    let cartDetails = [];
    let originalTotalPrice = 0;
    let currentTotalPrice = 0;

    // Hàm format tiền VND
    const formatPrice = (price) => Number(price).toLocaleString('vi-VN') + ' VND';
    
    // Kiểm tra giỏ hàng rỗng 
    const checkEmptyCart = () => {
        if (!cartDetails || cartDetails.length === 0) {
            window.location.href = '/phone-ecommerce-chat/customer/cart'; 
            return true;
        }
        return false;
    }
    // Hàm tính tổng tiền
    const calculateTotal = (items) => {
        return items.reduce((total, item) => {
            return total + (item.price * item.cartQuantity);
        }, 0);
    };

    // Hàm cập nhật hiển thị tổng tiền
    const updateTotalDisplay = (total) => {
        currentTotalPrice = total;
        $('#totalPrice').text(formatPrice(total));

        // Disable nút đặt hàng nếu tổng tiền = 0
        $('#submitButton').prop('disabled', total <= 0);
    };

    // Hàm render sản phẩm
    const renderProducts = () => {
        if (checkEmptyCart()) return;

        const productCheckoutContainer = $('#productCheckout');
        const productDetailContainer = $('#productDetailCheckout');

        // Render sản phẩm bên summary
        const productElements = cartDetails.map((item, index) => `
            <li class="d-flex justify-content-between flex-column flex-md-row align-items-center" style="gap:10px; margin-bottom: 10px;">
                <span class="text-truncate" style="max-width: 230px;">
                    ${item.product_name} 
                    <span class="text-muted">x ${item.cartQuantity}</span>
                </span>
                <span class="font-weight-bold text-danger">
                    ${formatPrice(item.price * item.cartQuantity)}
                </span>
            </li>
        `).join('');

        // Render bảng chi tiết sản phẩm
        const detailElements = cartDetails.map((item, index) => `
            <tr>
                <td class="align-middle">${index + 1}</td>
                <td class="align-middle">
                    <img src="${IMAGES_PATH}/${item.image}" 
                         alt="${item.product_name}" 
                         class="img-thumbnail"
                         style="width: 50px; height: 50px; object-fit: cover;">
                </td>
                <td class="align-middle font-weight-bold">${item.product_name}</td>
                <td class="align-middle text-center">${item.cartQuantity}</td>
                <td class="align-middle text-right text-danger font-weight-bold">
                    ${formatPrice(item.cartQuantity * item.price)}
                </td>
            </tr>
        `).join('');

        productCheckoutContainer.html(productElements);
        productDetailContainer.html(detailElements);
        
        // Tính và hiển thị tổng tiền ban đầu
        originalTotalPrice = calculateTotal(cartDetails);
        updateTotalDisplay(originalTotalPrice);
    };

    // Xử lý mã giảm giá
    $('#promotion_submit').click(function() {
        const code = $('#promotion_add').val().trim();
        
        if (!code) {
            return showToast('Vui lòng nhập mã giảm giá', false);
        }

        $.ajax({
            url: `http://localhost/phone-ecommerce-chat/customer/checkout/getPromotionByCode`,
            type: 'POST',
            data: { promotion_code: code },
            success: function(res) {
                if (!res.data || res.data.promotion_used === 1) {
                    return showToast('Mã giảm giá không đúng hoặc đã được sử dụng', false); 
                }

                const promotion = res.data;
                $('#promotion').text(`Giảm ${promotion.value}% - Mã: ${promotion.promotion_code}`);
                $('#promotion_add').val('');
                $('#promotion_id').val(promotion.promotion_id);

                // Tính lại giá sau khi giảm
                const discountAmount = (originalTotalPrice * promotion.value) / 100;
                const finalPrice = originalTotalPrice - discountAmount;
                updateTotalDisplay(finalPrice);

                showToast('Áp dụng mã giảm giá thành công', true);
            },
            error: function() {
                showToast('Có lỗi xảy ra khi áp dụng mã giảm giá', false);
            }
        });
    });
    
    // Thêm loading state cho buttons
    const setLoading = (isLoading) => {
        if (isLoading) {
            $('#submitButton').prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2"></span>Đang xử lý...');
            $('#promotion_submit').prop('disabled', true);
        } else {
            $('#submitButton').prop('disabled', false).html('Đặt hàng');
            $('#promotion_submit').prop('disabled', false);
        }
    };

    // Xử lý đặt hàng
    // Xử lý submit form
    $('#submitButton').click(function(e) {
        e.preventDefault();
        $('.error-message').remove();
        
        if (checkEmptyCart()) return;

        // Thu thập và validate dữ liệu form
        const formData = {
            name: $('input[name="name_receiver"]').val().trim(),
            phone: $('input[name="phone_receiver"]').val().trim(),
            city: $('#city option:selected').text(),
            district: $('#district option:selected').text(),
            ward: $('#ward option:selected').text(),
            address_detail: $('#address_detail').val().trim(),
            notes: $('textarea[name="notes"]').val().trim()
        };

        if (!validateFormData(formData)) {
            $('html, body').animate({
                scrollTop: $('.error-message').first().offset().top - 100
            }, 500);
            return;
        }

        setLoading(true);

        // Xử lý đặt hàng
        const address = `${formData.address_detail}, ${formData.ward}, ${formData.district}, ${formData.city}`;
        handleCheckout(formData.name, formData.phone, address, formData.notes);
    });

    // Validate form
    const validateFormData = (data) => {
        let isValid = true;

        if (!data.name) {
            showError('name_receiver', 'Vui lòng nhập tên người nhận');
            isValid = false; 
        }

        if (!data.phone) {
            showError('phone_receiver', 'Vui lòng nhập số điện thoại');
            isValid = false;
        } else if (!/^\d{10}$/.test(data.phone)) {
            showError('phone_receiver', 'Số điện thoại không hợp lệ');
            isValid = false;
        }

        // Validate địa chỉ chi tiết
        if (data.city === "Chọn tỉnh thành") {
            showError('city', 'Vui lòng chọn tỉnh thành');
            isValid = false;
        }
        if (data.district === "Chọn quận huyện") {
            showError('district', 'Vui lòng chọn quận huyện'); 
            isValid = false;
        }
        if (data.ward === "Chọn phường xã") {
            showError('ward', 'Vui lòng chọn phường xã');
            isValid = false;
        }
        if (!data.address_detail) {
            showError('address_detail', 'Vui lòng nhập địa chỉ chi tiết');
            isValid = false;
        }

        return isValid;
    };

    // Hiển thị lỗi
    const showError = (fieldName, message) => {
        $(`<span class="error-message text-danger">${message}</span>`)
            .insertAfter(`[name="${fieldName}"]`);
    };

    const validateCartData = (data) => {
        return data && Array.isArray(data) && data.length > 0 && 
            data.every(item => (
                item.product_id && 
                item.cartQuantity && 
                item.price &&
                !isNaN(parseInt(item.cartQuantity)) && 
                !isNaN(parseFloat(item.price))
            ));
    };

    const parseCartItem = (item) => {
        return {
            product_id: parseInt(item.product_id),
            quantity: parseInt(item.cartQuantity),
            price: parseFloat(item.price)
        };
    };
    
    const debug = {
        log: function(message, data = null) {
            console.log(`[DEBUG] ${message}`, data || '');
        },
        error: function(message, error = null) {
            console.error(`[ERROR] ${message}`, error || '');
        }
    };


    const handleCheckout = (name, phone, address, notes) => {
        setLoading(true);
        const cartDetails = JSON.parse(localStorage.getItem('cartDetails'));
        if (!cartDetails || !Array.isArray(cartDetails) || cartDetails.length === 0) {
            showToast('Có lỗi với dữ liệu giỏ hàng', false);
            setLoading(false);
            window.location.href = '/phone-ecommerce-chat/customer/cart';
            return;
        }

        const orderData = {
            name_receiver: name,
            phone_receiver: phone,
            address_receiver: address,
            notes: notes || '',
            total_price: currentTotalPrice,
            promotion_id: $('#promotion_id').val() || null,
            listProductDetail: cartDetails.map(item => ({
                product_id: parseInt(item.product_id),
                quantity: parseInt(item.cartQuantity),
                price: parseFloat(item.price)
            }))
        };

        if ($('#promotion_id').val()) {
            orderData.promotion_id = $('#promotion_id').val();
        }

        console.log('Sending order data:', orderData);

        $.ajax({
            url: `http://localhost/phone-ecommerce-chat/customer/checkout/store`,
            type: 'POST',
            data: orderData,
            success: function(res) {
                console.log('Server response:', res);
                setLoading(false);
                if (res.status === 200) {
                    showToast('Đặt hàng thành công', true);
                    localStorage.removeItem('cartDetails');
                    window.location.href = '/phone-ecommerce-chat/customer/checkout/success';
                } else {
                    showToast(res.message || 'Có lỗi xảy ra', false);
                }
            },
            error: function(err) {
                console.error('Error:', err);
                setLoading(false);
                showToast('Có lỗi xảy ra khi đặt hàng', false);
            }
        });
    };

 
    const init = () => {
    try {
        // Lấy và validate dữ liệu cart
        const cartDetailsString = localStorage.getItem('cartDetails');
        if (!cartDetailsString) {
            showToast('Không có dữ liệu giỏ hàng', false);
            setTimeout(() => {
                window.location.href = '/phone-ecommerce-chat/customer/cart';
            }, 1000);
            return;
        }

        cartDetails = JSON.parse(cartDetailsString);
        if (!cartDetails || !Array.isArray(cartDetails) || cartDetails.length === 0) {
            showToast('Dữ liệu giỏ hàng không hợp lệ', false);
            setTimeout(() => {
                window.location.href = '/phone-ecommerce-chat/customer/cart';
            }, 1000);
            return;
        }

        // Khởi tạo các thành phần
        initAddress();
        renderProducts();
        
    } catch (error) {
        console.error('Error initializing checkout:', error);
        showToast('Có lỗi xử lý dữ liệu giỏ hàng', false);
        setTimeout(() => {
            window.location.href = '/phone-ecommerce-chat/customer/cart';
        }, 1000);
    }
};

    init();
});
</script>