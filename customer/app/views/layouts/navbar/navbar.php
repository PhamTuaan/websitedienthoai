<!-- Header Section Begin -->
<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-lg-2">
                <div class="header__logo">
                    <a href="<?php echo URL_APP . '/home' ?>" class="text-dark font-weight-bold text-xl" style="font-size: 1.2rem;">AUGENTERN SHOP</a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7">
                <nav class="header__menu">
                    <ul>
                        <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' .  '/home' ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/home' ?>">Trang chủ</a></li>
                        <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' . '/shop' || preg_match("/shop\/product-detail\/[0-9]+/", $_SERVER['REQUEST_URI']) ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/shop' ?>">Shop</a></li>
                        <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' . '/blog' ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/blog' ?>">Blog</a></li>
                        <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' . '/contact' ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/contact' ?>">Liên hệ</a></li>
                        <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' . '/cart' ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/cart' ?>">Giỏ hàng</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="header__right d-flex align-items-center gap-2">
                    <?php if (isset($_SESSION['auth'])) : ?>
                        <div class="dropdown">
                            <a class="btn btn-transparent dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo isset($_SESSION['auth']['customer_name']) ? $_SESSION['auth']['customer_name'] : ''; ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="<?php echo URL_APP . '/cart' ?>">Giỏ hàng</a></li>
                                <li><a class="dropdown-item" href="<?php echo URL_APP . '/user/orderHistory' ?>">Lịch sử đặt hàng</a></li>
                                <hr>
                                <li><a class="dropdown-item" href="<?php echo URL_APP . '/user/profile' ?>">Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="<?php echo URL_APP . '/auth/forgotpassword' ?>">Đổi mật khẩu?</a></li>
                                <li><a class="dropdown-item text-danger logout-link" href="#"><i class="fa fa-sign-out mr-1" aria-hidden="true"></i>Đăng xuất</a></li>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="header__right__auth">
                            <a href="<?php echo URL_APP . '/auth/login' ?>">Đăng nhập</a>
                            <a href="<?php echo URL_APP . '/auth/register' ?>">Đăng ký</a>
                        </div>
                    <?php endif; ?>
                    <ul class="header__right__widget">
                        <!-- <li><span class="icon_search search-switch"></span></li> -->
                        <li><a href="<?php echo URL_APP . '/user/orderHistory' ?>"><i class="fa fa-history" aria-hidden="true"></i></a></li>
                        <li><a href="<?php echo URL_APP . '/cart' ?>"><span class="icon_bag_alt"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="canvas__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<!-- Header Section End -->

<script>
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/customer"

    $(document).ready(function() {
        $('.logout-link').click(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: URL_GLOBAL + '/auth/logout',
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.status === 200) {
                        showToast(res.message, true);
                        window.location.reload();
                    } else {
                        showToast(res.message, false);
                    }
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });
    });
</script>