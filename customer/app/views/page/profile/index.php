<!-- Breadcrumb Begin -->
<div class="breadcrumb-option pt-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?php echo URL_APP . '/home' ?>"><i class="fa fa-home"></i> Trang chủ</a>
                    <span>Tài khoản</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->


<section class="shop quad">
    <div class="container">
        <div class="top-title">
            <h3>Hồ Sơ Của Tôi</h3>
            <p class="text-muted">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
            <button type="button" class="btn btn-primary">
                Điểm sếp hạng: <span class="badge badge-light"><?php echo $customer['customer_points'] ?> điểm</span>
            </button>
            <?php if ($customer['customer_points'] < 500) { ?>
                <span class="badge badge-pill badge-secondary">Chưa có xếp hạng</span>
            <?php } elseif ($customer['customer_points'] < 1000) { ?>
                <span class="badge badge-pill badge-secondary">Hạng bạc</span>
            <?php } elseif ($customer['customer_points'] < 2000) { ?>
                <span class="badge badge-pill badge-warning">Hạng vàng</span>
            <?php } else { ?>
                <span class="badge badge-pill badge-info">Hạng kim cương</span>
            <?php } ?>
                <hr class="dropdown-divider">
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="checkout__form__input">
                    <span>Họ và tên<span class="text-danger">*</span></span>
                    <input class="form-control" type="text" placeholder="Nguyễn Văn An"
                        value="<?php echo isset($_SESSION['auth']['customer_name']) ? $_SESSION['auth']['customer_name'] : "" ?>"
                        name="customer_name" required>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="checkout__form__input">
                    <span>Email<span class="text-danger">*</span></span>
                    <input class="form-control" type="text" placeholder="abc@gmail.com"
                        value="<?php echo isset($_SESSION['auth']['email']) ? $_SESSION['auth']['email'] : "" ?>"
                        name="email" required readonly>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="checkout__form__input">
                    <span>Số điện thoại<span class="text-danger">*</span></span>
                    <input class="form-control" type="number" placeholder="+84"
                        value="<?php echo isset($_SESSION['auth']['phone']) ? $_SESSION['auth']['phone'] : "" ?>"
                        name="phone" required>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="checkout__form__input">
                    <span>Ngày sinh<span class="text-danger">*</span></span>
                    <input class="form-control" type="date" id="birthday"
                        value="<?php echo isset($_SESSION['auth']['birthday']) ? $_SESSION['auth']['birthday'] : "" ?>"
                        name="birthday" required>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="checkout__form__input">
                    <span>Địa chỉ <span class="text-danger">*</span></span>
                    <input class="form-control" type="text" placeholder="Tên đường, phường, xã"
                        value="<?php echo isset($_SESSION['auth']['address']) ? $_SESSION['auth']['address'] : "" ?>"
                        name="address" required>
                </div>
            </div>
        </div>
        <div class="mb-3 d-flex justify-content-end">
            <button type="button" class="site-btn rounded-0 mt-5">Lưu thay đổi</button>
        </div>
        <span class="text-dark mt-3 d-flex justify-content-end">
            Bạn muốn đổi mật khẩu mới? <a href="<?php echo URL_APP . '/auth/forgotPassword' ?>"
                class="text-primary ml-2"> Thay đổi mật khẩu.</a>
        </span>
    </div>
</section>

<script>
    console.log(123)
    $(document).ready(function () {
        $('.site-btn').click(function (event) {
            event.preventDefault();

            // Get input values
            const customerName = $('input[name="customer_name"]').val();
            const email = $('input[name="email"]').val();
            const phone = $('input[name="phone"]').val();
            const birthday = $('input[name="birthday"]').val();
            const address = $('input[name="address"]').val();

            if (!customerName || !email || !phone || !birthday || !address) {
                alert('Vui lòng điền đầy đủ thông tin!');
                return;
            }

            const data = {
                customer_name: customerName,
                email: email,
                phone: phone,
                birthday: birthday,
                address: address
            };

            console.log(data);

            $.ajax({
                url: 'http://localhost/phone-ecommerce-chat/customer/user/updateProfile',
                method: 'POST',
                data: data,
                dataType: 'json', // Assuming the server response is JSON
                success: function (res) {
                    // Handle successful res
                    if (res.status === 200) {
                        showToast(res.message, true);
                        window.location.reload();
                        // Optionally, update the UI or redirect to another page
                    } else {
                        showToast('Lỗi khi cập nhật hồ sơ: ' + res.message, false);
                    }
                },
                error: function (error) {
                    console.error('Lỗi khi cập nhật hồ sơ:', error);
                    showToast('Đã xảy ra lỗi khi cập nhật hồ sơ!', false);
                }
            });
        });
    });
</script>