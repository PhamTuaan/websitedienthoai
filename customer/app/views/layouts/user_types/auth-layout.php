<!-- Navbar -->
<?php include(__DIR__ . '/../navbar/navbar.php'); ?>

<!-- Page -->
<div class="container">
    <div class="row mt-5">
        <div class="col-3 p-3">
            <div class="d-flex justify-content-center align-items-center mb-2">
                <img src="<?php echo SCRIPT_ROOT . '/assets/img/logo.png' ?>" alt="" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                <div class="text-truncate text-dark ml-2 font-weight-bold"><?php echo $_SESSION['auth']['customer_name'] ?></div>
            </div>
            <ul class="list-unstyled mt-5">
                <li class="d-flex justify-content-center align-items-center" style="gap: 12px;">
                    <i class="fa fa-address-book-o" aria-hidden="true"></i>
                    <a href="<?php echo URL_APP . '/user/profile' ?>" class="text-dark">Tài khoản của tôi</a>
                </li>
                <li class="d-flex justify-content-center align-items-center" style="gap: 12px;">
                    <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                    <a href="<?php echo URL_APP . '/user/orderHistory' ?>" class="text-dark">Lịch sử đơn hàng</a>
                </li>
            </ul>
        </div>
        <div class="col-9 p-3">
            <?php require_once "./app/views/page/{$page}.php" ?>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include(__DIR__ . '/../footer/footer.php'); ?>