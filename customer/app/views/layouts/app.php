<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo SCRIPT_ROOT ?>/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?php echo SCRIPT_ROOT ?>/assets/img/favicon.png">
    <title>
        Augentern Shop
    </title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />

    <!-- Ajax -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/jquery-3.3.1.min.js"></script>

    <!-- Toast -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Css Styles -->
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/bootstrap.min.css" type=" text/css">
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/font-awesome.min.css" type=" text/css">
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/elegant-icons.css" type=" text/css">
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/jquery-ui.min.css" type=" text/css">
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/magnific-popup.css" type=" text/css">
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/owl.carousel.min.css" type=" text/css">
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/slicknav.min.css" type=" text/css">
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/style.css" type=" text/css">
    <link rel="stylesheet" href=" <?php echo SCRIPT_ROOT; ?>/assets/css/custom.css" type=" text/css">
</head>

<style>
    .customer-search-input input {
        width: 500px;
        font-size: 40px;
        border: none;
        border-bottom: 2px solid #dddddd;
        background: 0 0;
        color: #999;
    }
</style>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            <li><span class="icon_search search-switch"></span></li>
            <li><a href="<?php echo URL_APP . '/user/order-history' ?>"><i class="fa fa-history" aria-hidden="true"></i></a></li>
            <li><a href="<?php echo URL_APP . '/cart' ?>"><span class="icon_bag_alt"></span>
                    <div class="tip"><?php echo isset($cartCount) ? $cartCount : 0; ?></div>
                </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="/"><img src="<?php echo SCRIPT_ROOT ?>/assets/img/logo.png" alt=""></a>
        </div>
        <div id=" mobile-menu-wrap">
        </div>
        <div class="header__right d-flex flex-column align-items-start gap-2">
            <nav class="header__menu d-block">
                <ul class="d-flex flex-column align-items-start">
                    <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' .  '/home' ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/home' ?>">Trang chủ</a></li>
                    <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' . '/shop' || preg_match("/shop\/product-detail\/[0-9]+/", $_SERVER['REQUEST_URI']) ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/shop' ?>">Shop</a></li>
                    <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' . '/blog' ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/blog' ?>">Blog</a></li>
                    <li class="<?php echo ($_SERVER['REQUEST_URI'] == '/phone-ecommerce-chat/customer' . '/contact' ? 'active' : ''); ?>"><a href="<?php echo '/phone-ecommerce-chat/customer' . '/contact' ?>">Liên hệ</a></li>
                </ul>
            </nav>
            <hr>
            <?php if (isset($_SESSION['auth'])) : ?>
                <ul style="list-style: none;" class="d-flex flex-column align-items-start">
                    <li><a class="dropdown-item" href="<?php echo URL_APP . '/user/profile' ?>">Thông tin cá nhân</a></li>
                    <li><a class="dropdown-item" href="<?php echo URL_APP . '/user/orderHistory' ?>">Lịch sử đặt hàng</a></li>
                    <li><a class="dropdown-item" href="<?php echo URL_APP . '/cart' ?>">Giỏ hàng</a></li>
                    <li><a class="dropdown-item" href="<?php echo URL_APP . '/auth/forgotpassword' ?>">Quên mật khẩu?</a></li>
                    <li><a class="dropdown-item text-danger logout-link" href="#"><i class="fa fa-sign-out mr-1" aria-hidden="true"></i>Đăng xuất</a></li>
                </ul>
            <?php else : ?>
                <div class="offcanvas__auth">
                    <a href="<?php echo URL_APP . '/auth/login' ?>">Đăng nhập</a>
                    <a href="<?php echo URL_APP . '/auth/register' ?>">Đăng ký</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <?php
    $currentUrl = $_SERVER['REQUEST_URI'];
    $useAuthLayout = strpos($currentUrl, '/user') !== false;

    if ($useAuthLayout) {
        include(__DIR__ . '/user_types/auth-layout.php');
    } else {
        include(__DIR__ . '/user_types/main-layout.php');
    }
    ?>


    <!-- Search Begin -->
    <form action="<?php echo URL_APP; ?>/shop/shopSearch" method="GET">
        <div class="search-model">
            <div class="h-100 d-flex align-items-center justify-content-center">
                <div class="search-close-switch">+</div>
                <div class="customer-search-input">
                    <input type="text" id="search-input" name="search" placeholder="Search here.....">
                </div>
            </div>
        </div>
    </form>
    <!-- Search End -->

    <!-- Chatbox -->
    <?php include(__DIR__ . '/box.php'); ?>

    <!-- Js Plugins -->
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/bootstrap.min.js"></script>
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/jquery.magnific-popup.min.js"></script>
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/jquery-ui.min.js"></script>
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/mixitup.min.js"></script>
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/jquery.countdown.min.js"></script>
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/jquery.slicknav.js"></script>
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/owl.carousel.min.js"></script>
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/jquery.nicescroll.min.js"></script>

    <!-- Custom css -->
    <script src=" <?php echo SCRIPT_ROOT; ?>/assets/js/main.js"></script>
    <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/custom.js"></script>


    <script>
        $(document).ready(function() {
            $('.logout-link').click(function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: 'http://localhost/phone-ecommerce-chat/customer/auth/logout',
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

            var proQty = $(".pro-qty");
            proQty.prepend('<span class="dec qtybtn">-</span>');
            proQty.append('<span class="inc qtybtn">+</span>');
            proQty.on("click", ".qtybtn", function() {
                var $button = $(this);
                var oldValue = $button.parent().find("input").val();
                if ($button.hasClass("inc")) {
                    var newVal = parseFloat(oldValue) + 1;
                } else {
                    // Don't allow decrementing below zero
                    if (oldValue > 0) {
                        var newVal = parseFloat(oldValue) - 1;
                    } else {
                        newVal = 0;
                    }
                }
                $button.parent().find("input").val(newVal);
            });
        });
    </script>
</body>
</html>