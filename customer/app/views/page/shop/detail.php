<style>
    .custom-button-cart {
        font-size: 18px;
        color: #111111;
        display: block;
        height: 45px;
        width: 45px;
        background: #ffffff;
        line-height: 48px;
        text-align: center;
        border-radius: 50%;
        -webkit-transition: all, 0.5s;
        -o-transition: all, 0.5s;
        transition: all, 0.5s;
    }

    .custom-button-cart:hover {
        background: #ca1515;
    }

    .custom-button-cart:hover span {
        color: #ffffff;
        -webkit-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }

    .review-item {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .review-header {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .review-date {
        color: #666;
        font-size: 0.9em;
    }

    .review-rating {
        color: #ffd700;
        margin: 5px 0;
    }

    .review-content {
        margin-top: 10px;
    }
</style>

<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?php echo URL_APP . '/home' ?>"><i class="fa fa-home"></i> Home</a>
                    <a href=""><?php echo $product['category_name'] ?></a>
                    <span><?php echo $product['product_name'] ?> </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <input type="hidden" value="<?php echo $product['product_id'] ?>" id="hiddenProductId">
    <input type="hidden" value="<?php echo $product['category_id'] ?>" id="hiddenCatId">
    <div class="container">
        <div id="product_detail"></div>

        <h2 class="text-dark font-weight-bold mb-3">Các sản phẩm cùng loại</h2>
        <div class="row" id="related_products"></div>
    </div>
</section>
<!-- Product Details Section End -->

<script>
    const productId = document.getElementById('hiddenProductId').value;
    const catId = document.getElementById('hiddenCatId').value;
    const URL = 'http://localhost/phone-ecommerce-chat/customer/shop';
    const IMAGES_URL = 'http://localhost/phone-ecommerce-chat/storages/public';

    const fetchProductDetail = () => {
        let hasBoughtProduct = false;
        let hasReviewed = false;
        let product = null;
        let reviews = [];

        // Kiểm tra lịch sử mua hàng
        $.ajax({
            url: `${URL}/checkPurchaseHistory/${productId}`,
            method: 'GET',
            dataType: 'json',
            async: false,
            success: function(res) {
                hasBoughtProduct = res.hasBought;
            },
            error: function(error) {
                console.error('Lỗi khi kiểm tra lịch sử mua hàng:', error);
            }
        });

        // Kiểm tra đánh giá
        if (hasBoughtProduct) {
            $.ajax({
                url: `${URL}/checkProductReview/${productId}`,
                method: 'GET',
                dataType: 'json',
                async: false,
                success: function(res) {
                    hasReviewed = res.hasReviewed;
                },
                error: function(error) {
                    console.error('Lỗi khi kiểm tra đánh giá:', error);
                }
            });
        }

        // Lấy thông tin sản phẩm và đánh giá
        $.ajax({
            url: `${URL}/showProductDetailData/${productId}`,
            method: 'GET',
            dataType: 'json',
            async: false,
            success: function(res) {
                product = res.data;
                reviews = res.reviews;
            },
            error: function(error) {
                console.error('Lỗi khi lấy dữ liệu sản phẩm:', error);
            }
        });

        if (product) {
            renderProductDetail(product, reviews, hasBoughtProduct, hasReviewed);
        }
    }

    const renderProductDetail = (product, product_reviews, hasBoughtProduct, hasReviewed) => {
        const productDetailHtml = `
            <div class="row">
                <div class="col-lg-6">
                    <a class="pt active" href="#product-1">
                        <img loading="lazy" src="${IMAGES_URL}/${product.image}" alt="">
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="product__details__text">
                        <h3>${product.product_name} <span>Danh mục: ${product.category_name}</span></h3>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span>( ${product_reviews.length} Đánh giá )</span>
                        </div>
                        <div class="product__details__price">${formatPrice(product.price)} <span>${formatPrice(Number(product.price) + 450000)}</span></div>
                        <p>${product.description.substring(0, 250)}...</p>
                        <div class="product__details__button">
                            <div class="quantity">
                                <span>Số lượng:</span>
                                <div class="pro-qty">
                                    <span class="dec qtybtn" style="${parseInt(product.quantity) === 0 ? 'pointer-events: none;' : ''}">-</span>
                                    <input type="text" value="1" id="addCartQt" name="quantity" ${parseInt(product.quantity) === 0 ? 'disabled' : ''} />
                                    <span class="inc qtybtn" style="${parseInt(product.quantity) === 0 ? 'pointer-events: none;' : ''}">+</span>
                                </div>
                            </div>
                            <input type="hidden" id="addCartPId" value="${product.product_id}">
                            <button type="button" class="addCartButton cart-btn outline-none" style="${parseInt(product.quantity) === 0 ? 'pointer-events: none;' : ''}">
                                <span class="icon_bag_alt mr-2"></span>${parseInt(product.quantity) === 0 ? "Đã hết hàng" : "Thêm vào giỏ hàng"}
                            </button>
                        </div>
                        <div class="product__details__widget">
                            <ul>
                                <li>
                                    <span>Khuyến mãi:</span>
                                    <p>Miễn phí vận chuyển</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Đánh giá (${product_reviews.length})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Mô tả</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <h6>Đánh giá sản phẩm</h6>
                                <div id="product-reviews">
                                    ${product_reviews && product_reviews.length > 0 ?
                                        product_reviews.map(review => `
                                            <div class="review-item">
                                                <div class="review-header">
                                                    <span class="review-author">Khách hàng #${review.customer_id}</span>
                                                    <span> - </span>
                                                    <span class="review-date">${new Date(review.created_at).toLocaleDateString()}</span>
                                                </div>
                                                <div class="review-rating">
                                                    ${'★'.repeat(review.rate)}${'☆'.repeat(5 - review.rate)}
                                                </div>
                                                <p class="review-content">${review.content}</p>
                                            </div>
                                        `).join('')
                                        : '<p>Chưa có đánh giá nào cho sản phẩm này.</p>'
                                    }
                                </div>
                                
                                ${hasBoughtProduct ? 
                                    hasReviewed ? 
                                        `<div class="alert alert-info mt-4">
                                            <p>Bạn đã đánh giá sản phẩm này.</p>
                                        </div>`
                                        : 
                                        `<div class="review-form mt-4">
                                            <h6>Thêm đánh giá của bạn</h6>
                                            <form id="reviewForm">
                                                <input type="hidden" id="reviewCustomerId" value="<?php echo isset($_SESSION['auth']) ? $_SESSION['auth']['customer_id'] : ''; ?>">
                                                <div class="form-group">
                                                    <label for="reviewContent">Nội dung đánh giá:</label>
                                                    <textarea class="form-control" id="reviewContent" rows="3" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="reviewRating">Số sao:</label>
                                                    <select class="form-control" id="reviewRating" required>
                                                        <option value="">Chọn số sao</option>
                                                        <option value="1">1 sao</option>
                                                        <option value="2">2 sao</option>
                                                        <option value="3">3 sao</option>
                                                        <option value="4">4 sao</option>
                                                        <option value="5">5 sao</option>
                                                    </select>
                                                </div>
                                                <button type="submit" id="reviewSubmit" class="site-btn">Gửi đánh giá</button>
                                            </form>
                                        </div>`
                                    :
                                    `<div class="alert alert-info mt-4">
                                        <p>Bạn cần mua sản phẩm này để có thể đánh giá.</p>
                                    </div>`
                                }
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <h6>Mô tả</h6>
                                <p>${product.description}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#product_detail').html(productDetailHtml);
    }

    const fetchRelatedProducts = () => {
        $.ajax({
            url: `${URL}/getRelatedProducts/${productId}/${catId}`,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                renderRelatedProducts(res.data);
            },
            error: function(error) {
                console.error('Lỗi khi lấy dữ liệu sản phẩm liên quan:', error);
            }
        });
    }

    const renderRelatedProducts = (products) => {
        const relatedProductsContainer = document.getElementById("related_products");

        if (!products || products.length === 0) {
            relatedProductsContainer.innerHTML = `
                <h4 class="text-center fs-4 mx-auto mt-5 font-weight-bold w-75">
                    Không có sản phẩm nào được tìm thấy!
                </h4>
            `;
            return;
        }

        const productHtml = products.map((product, index) => `
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg" data-setbg="${IMAGES_URL}/${product.image}" 
                         style="background-image:url('${IMAGES_URL}/${product.image}'); background-size: contain; background-position: center center; background-repeat:no-repeat">
                        ${index % 3 === 0 ? '<div class="label new">New</div>' : ''}
                        <ul class="product__hover">
                            <li><a href="${IMAGES_URL}/${product.image}" class="image-popup"><span class="arrow_expand"></span></a></li>
                            <li>
                                <a href="${URL}/detail/${product.product_id}"><span class="icon_search"></span></a>
                            </li>
                            <li>
                                <form action="#">
                                    <input type="hidden" value="${product.product_id}" name="product_id">
                                    <input type="hidden" value="1" name="quantity">
                                    <button type="submit" class="border-0 outline-none custom-button-cart"><span class="icon_bag_alt"></span></button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="${URL}/detail/${product.product_id}">${product.product_name}</a></h6>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="product__price">
                        <a href="${URL}/detail/${product.product_id}" class="text-dark">
                                ${formatPrice(product.price)} VND
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        relatedProductsContainer.innerHTML = productHtml;
    }

    const formatPrice = (price) => {
        return `${Number(price).toLocaleString('vi-VN')}`;
    }

    $(document).ready(function() {
        fetchProductDetail();
        fetchRelatedProducts();

        // Xử lý thêm vào giỏ hàng
        $(document).on('click', '.addCartButton', function() {
            const quantity = $('#addCartQt').val();
            const product_id = $('#addCartPId').val();

            $.ajax({
                url: `http://localhost/phone-ecommerce-chat/customer/cart/store`,
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(res) {
                    if (res.status === 201 || res.status === 200) {
                        showToast(res.message, true);
                    }
                },
                error: function(error) {
                    console.error('Lỗi khi thêm vào giỏ hàng:', error);
                    showToast("Vui lòng đăng nhập trước khi thêm sản phẩm", false);
                }
            });
        });

        // Xử lý tăng giảm số lượng
        $('#product_detail').on('click', '.pro-qty .qtybtn', function() {
            var $button = $(this);
            var $input = $button.parent().find("input");
            var oldValue = parseInt($input.val());
            
            if ($button.hasClass("inc")) {
                var newVal = oldValue + 1;
            } else {
                var newVal = oldValue > 1 ? oldValue - 1 : 1;
            }
            
            $input.val(newVal);
        });

        // Xử lý submit form đánh giá
        $('#product_detail').on('submit', '#reviewForm', function(e) {
            e.preventDefault();
            const customerId = $('#reviewCustomerId').val();
            const content = $('#reviewContent').val();
            const rating = $('#reviewRating').val();

            if (!customerId) {
                showToast("Vui lòng đăng nhập để gửi đánh giá", false);
                return;
            }

            if (!content || !rating) {
                showToast("Vui lòng điền đầy đủ thông tin đánh giá", false);
                return;
            }

            $.ajax({
                url: `${URL}/addProductReview`,
                method: 'POST',
                data: {
                    product_id: productId,
                    content: content,
                    rate: rating
                },
               
                success: function(response) {
                    if (response.status === 201) {
                        showToast("Đánh giá của bạn đã được gửi thành công", true);
                        fetchProductDetail(); 
                    } else {
                        showToast(response.message || "Có lỗi xảy ra khi gửi đánh giá", false);
                    }
                },
                error: function(error) {
                    console.error('Lỗi khi gửi đánh giá:', error);
                    showToast("Có lỗi xảy ra khi gửi đánh giá", false);
                }
            });
        });

        // Xử lý popup hình ảnh
        $('.image-popup').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    });

    // Hàm hiển thị thông báo
    function showToast(message, isSuccess) {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: isSuccess ? "#4caf50" : "#f44336",
        }).showToast();
    }
</script>