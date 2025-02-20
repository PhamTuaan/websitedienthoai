<main class="main-content mt-0">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-bolder text-info text-gradient">Chào mừng đã trở lại</h3>
                                <p class="mb-0">Tạo tài khoản mới<br></p>
                                <p class="mb-0">Hoặc đăng nhập với tài khoản hiện có</p>
                            </div>
                            <div class="card-body">
                                <form role="form" method="POST" action="<?php echo URL_APP . '/auth/signIn' ?>">
                                    <!-- CSRF Token -->
                                    <label>Email</label>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Nhập email của bản tại đây" value="" aria-label="email" aria-describedby="email-addon">
                                    </div>
                                    <label>Password</label>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" aria-label="Password" aria-describedby="password-addon">
                                    </div>
                                    <!--  -->
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe" checked="">
                                        <label class="form-check-label" for="rememberMe">Ghi nhớ</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Đăng nhập</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <small class="text-muted">Quên mật khẩu? Thay đổi mật khẩu
                                    <a href="/login/forgot-password" class="text-info text-gradient font-weight-bold">tại đây</a>
                                </small>
                                <p class="mb-4 text-sm mx-auto">
                                    Không có tài khoản?
                                    <a href="register" class="text-info text-gradient font-weight-bold">Đăng ký</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('<?php echo SCRIPT_ROOT; ?>/assets/img/curved-images/curved6.jpg')"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    $(document).ready(function() {
    $('form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res.status === 200) {
                    showToast(res.message, true);
                    setTimeout(function() {
                        window.location.href = '/phone-ecommerce-chat/admin/home';
                    }, 100);
                } else {
                    showToast(res.message, false);
                }
            },
            error: function(xhr) {
                showToast('Có lỗi xảy ra: ' + xhr.responseText, false);
            }
        });
    });
});

</script>