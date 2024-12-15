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
</style>

<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="//phone-ecommerce-chat/customer/home"><i class="fa fa-home"></i> Trang chủ</a>
                    <span>Shop</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Shop Section Begin -->
<section class="shop spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="shop__sidebar">
                    <div class="sidebar__categories">
                        <div class="section-title">
                            <h4>Tìm kiếm</h4>
                        </div>
                        <input type="text" class="form-control" placeholder="Tên sản phẩm" id="searchQuery" name="search" onkeyup="handleFilterChange()">
                    </div>
                    <div class="sidebar__categories">
                        <div class="section-title">
                            <h4>Danh mục</h4>
                        </div>
                        <div class="categories__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading active">
                                        <a data-toggle="collapse" data-target="#collapseOne">Hãng Sản Xuất</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><a href="<?php URL_APP . '/shop' ?>">Xóa bộ lọc</a></li>
                                                <span id="lstCategories"></span>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__categories">
                        <div class="section-title">
                            <h4>Giá sản phẩm</h4>
                        </div>
                        <div class="categories__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading active">
                                        <a data-toggle="collapse" data-target="#collapseOne">Lọc theo giá</a>
                                    </div>
                                    <div id="collapseTwo" class="collapse show" data-parent="#price-rang">
                                        <div class="card-body">
                                            <ul>
                                                <li><a href="<?php URL_APP . '/shop' ?>">Xóa bộ lọc</a></li>
                                                <span id="lstPrice">
                                                    <li><a onclick="filterByPriceRange(1000000,5000000)" href="#">1,000,000 - 5,000,000 VND</a></li>
                                                    <li><a onclick="filterByPriceRange(6000000,15000000)" href="#">6,000,000 - 15,000,000 VND</a></li>
                                                    <li><a onclick="filterByPriceRange(16000000,25000000)" href="#">16,000,000 - 25,000,000 VND</a></li>
                                                    <li><a onclick="filterByPriceRange(26000000,35000000)" href="#">26,000,000 - 35,000,000 VND</a></li>
                                                    <li><a onclick="filterByPriceRange(36000000,100000000)" href="#">36,000,000 VND +</a></li>
                                                </span>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="sidebar__filter">
                        <div class="section-title">
                            <h4>Mua sắm theo giá</h4>
                        </div>
                        <div class="filter-range-wrap">
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" data-min="1000000" data-max="45000000"></div>
                            <div class="range-slider">
                                <div class="price-input">
                                    <p>Giá:</p>
                                    <input type="text" class="font-weight-bold" id="minamount" onchange="handleFilterByPrice()" name="minPrice" placeholder="1000000">
                                    <input type="text" class="font-weight-bold" id="maxamount" onchange="handleFilterByPrice()" name="maxPrice" placeholder="45000000">
                                </div>
                            </div>
                            <br>
                            <div class="d-flex gap-1 justify-content-between">
                                <button type="button" class="btn btn-outline-dark w-75 mt-1 font-weight-bold" onclick="handleFilterByPrice()" id="filter">Tìm kiếm</button>
                                <a href="<?php URL_APP . '/shop' ?>" class="btn btn-danger font-weight-bold outline-none text-white" style="bottom: 0 !important; padding:10px 16px 10px 16px;"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="row mb-4">
                    <div class="col-12 col-lg-5">
                        <div id="productCount"></div>
                    </div>
                    <div class="col-12 col-lg-7">
                        <div id="productFilter">
                            <form id="filterForm">
                                <div class="d-flex flex-column align-items-end justify-content-center">
                                    <div>
                                        <select id="sortOrder" class="form-control" name="sort" onchange="handleFilterChange()">
                                            <option value="asc">Giá: Thấp đến Cao</option>
                                            <option value="desc">Giá: Cao đến Thấp</option>
                                        </select>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="row" id="lstProducts">
                </div>
                <div class="row" id="pagination"></div>
            </div>
        </div>
    </div>
</section>
<!-- Shop Section End -->

<script>
    const URL = "http://localhost/phone-ecommerce-chat/customer"
    const URL_IMAGES = "http://localhost/phone-ecommerce-chat/storages/public"
    let productState = []

    // Fetch 
    const fetchProducts = async () => {
        try {
            const response = await fetch(`${URL}/shop/all`);
            const data = await response.json();
            if (data.status === 200) {
                productState = data.data;
                renderProducts(data.data);
                renderProductCount(data.data);
            } else if (data.status === 204) {
                console.log(data.message);
            } else {
                console.log(data.message);
            }
        } catch (error) {
            showToast('Có lỗi xảy ra: ' + error, false);
        }
    }

    const fetchCategories = async () => {
        try {
            const response = await fetch(`http://localhost/phone-ecommerce-chat/admin/categories/activeCategories`);
            const data = await response.json();
            if (data.status === 200) {
                renderCategories(data.data);
            } else if (data.status === 204) {
                console.log(data.message);
            } else {
                console.log(data.message);
            }
        } catch (error) {
            showToast('Có lỗi xảy ra: ' + error, false);
        }
    }

    // Render 
    const renderProducts = (products) => {
        const productsContainer = document.getElementById('lstProducts');

        if (products === undefined) {
            return productsContainer.innerHTML = `
                    <h2 class="text-center fs-4 mx-auto font-weight-bold w-75">
                        Không có sản phẩm nào được tìm thấy.
                    </h2>`;
        }

        const productsHTML =
            products.map((product, index) => (
                `
                <div class="col-lg-4 col-md-6">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" style="background-image:url('${URL_IMAGES}/${product.image}'); background-size: contain; background-position: center center; background-repeat:no-repeat">
                                ${index /3 ===0 ? '<div class="label new">New</div>':''}
                                <ul class="product__hover">
                                    <li><a href="${URL_IMAGES}/${product.image}" class="image-popup"><span class="arrow_expand"></span></a></li>
                                    <li>
                                        <a href="${URL}/shop/detail/${product.product_id}"><span class="icon_search"></span></a>
                                    </li>
                                    <li>
                                        <input type="hidden" class="addCartPId" value="${product.product_id}">
                                        <input type="hidden" class="addCartQt" value="1" name="quantity">
                                        <button type="button" class="addCartButton border-0 outline-none custom-button-cart"><span class="icon_bag_alt"></span></button>
                                    </li>
                                </ul>
                            </div>

                            <div class="product__item__text">
                                <h6><a href="${URL}/shop/detail/${product.product_id}">${product.product_name}</a></h6>
                                <div class="rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="product__price">
                                    <a href="${URL}/shop/detail/${product.product_id}" class="text-dark">
                                        ${Number(product.price).toLocaleString('vi-VN')} VND
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `)).join('');

        productsContainer.innerHTML = productsHTML;
        renderProductCount(products);
    }

    const renderCategories = (categories) => {
        const categoriesContainer = document.getElementById('lstCategories');
        const categoryHTML = `
        <ul>
            ${categories.map((cat) => (
                `<li><a href="${URL}/shop/filterByCategory/${cat.category_id}">${cat.category_name}</a></li>`
            )).join('')}
        </ul>
    `;

        categoriesContainer.innerHTML = categoryHTML;
    };

    const renderProductCount = (products) => {
        const productCount = document.getElementById('productCount');
        const totalProducts = products.length;
        productCount.innerHTML = `Hiển thị ${totalProducts} trên tổng số ${totalProducts} sản phẩm`;
    };

    const handleFilterChange = (products) => {
        if (products.length === 0 || products === undefined) return;
        const sortOrder = document.getElementById('sortOrder').value;
        const searchQuery = document.getElementById('searchQuery').value.toLowerCase();
        const filteredProducts = products.filter((product) => {
            const productName = product.product_name.toLowerCase();
            return productName.includes(searchQuery);
        });
        const sortedProducts = filteredProducts.sort((a, b) => {
            if (sortOrder === 'asc') {
                return a.price - b.price;
            } else {
                return b.price - a.price;
            }
        });
        renderProducts(sortedProducts);
        renderProductCount(sortedProducts);
    };

    // function handleFilterByPrice() {
    //     let minAmount = document.getElementById('minamount').value.replace(/,/g, '') || '0';
    //     minAmount = minAmount.substring(1);
    //     minAmount = parseInt(minAmount);

    //     let maxAmount = document.getElementById('maxamount').value.replace(/,/g, '') || '0';
    //     maxAmount = maxAmount.substring(1);
    //     maxAmount = parseInt(maxAmount);

    //     console.log(minAmount);
    //     console.log(maxAmount);

    //     const filteredProducts = productState.filter((product) => {
    //         return product.price >= minAmount && product.price <= maxAmount;
    //     });

    //     console.log(filteredProducts);

    //     renderProducts(filteredProducts);
    //     renderProductCount(filteredProducts);
    // }

    const filterByPriceRange = async (minPrice, maxPrice) => {
        console.log(minPrice, maxPrice);

        try {
            const response = await fetch(`${URL}/shop/filterByPrice/${minPrice}/${maxPrice}`);
            const data = await response.json();
            if (data.status === 200) {
                productState = data.data;
                renderProducts(data.data);
                renderProductCount(data.data);
            } else if (data.status === 204) {
                console.log(data.message);
            } else {
                console.log(data.message);
            }
        } catch (error) {
            showToast('Có lỗi xảy ra: ' + error, false);
        }
    }

    // Actions 
    $(document).ready(function() {
        fetchProducts()
        fetchCategories()

        // categories - price FILTER
        $('#lstCategories').on('click', 'a', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                type: 'GET',
                url: url,
                success: function(res) {
                    console.log(res);
                    if (res.status === 200) {
                        console.log(res);
                        renderProducts(res.data);
                        // showToast(res.message, true);
                    }
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });

        // Sort and search filter
        $('#sortOrder').on('change', function() {
            handleFilterChange(productState);
        });

        $('#searchQuery').on('input', function() {
            handleFilterChange(productState);
        });

        // Add cart
        $(document).on('click', '.addCartButton', function() {
            const product_id = $(this).closest('li').find('.addCartPId').val();
            const quantity = $(this).closest('li').find('.addCartQt').val();

            console.log('run?');
            $.ajax({
                url: `${URL}/cart/store`,
                type: 'POST',
                data: {
                    product_id: product_id,
                    quantity: quantity
                },
                success: function(res) {
                    console.log(res);
                    if (res.status === 201) {
                        showToast(res.message, true);
                    } else if (res.status === 200) {
                        showToast(res.message, true);
                    }
                },
                error: function(error) {
                    showToast("Vui lòng đăng nhập trước khi thêm sản phẩm", false);
                    window.location.href = URL + '/auth/login';
                }
            });
        });
    });
</script>