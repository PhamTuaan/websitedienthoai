<div class="page-header section-height-75 my-5 py-5">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                <div class="card shadown-sm p-3 mt-8">
                    <?php if (isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                            <span class="alert-text text-white">
                                <?php echo $_SESSION['success']; ?></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                    <div class="card-header pb-0 text-left bg-transparent">
                        <h4 class="mb-0 mx-auto text-center text-info font-weight-bold">Quên mật khẩu? Nhập Email tại đây.</h4>
                    </div>
                    <div class="card-body">
                        <form action=" <?php echo URL_APP . '/auth/changePassword' ?> ">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <div class="">
                                    <input id="email" name="email" type="email" value="<?php echo $_SESSION['auth_admin']['email'] ?>" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password">Mật khẩu mới</label>
                                <div class="">
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation">Xác nhận mật khẩu</label>
                                <div class="">
                                    <input id="password-confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Password-confirmation" aria-label="Password-confirmation" aria-describedby="Password-addon">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-dark w-100 mt-4 mb-0">Lấy lại mật khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const URL = "http://localhost/phone-ecommerce-chat/customer"

    $(document).ready(function() {
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        $('form').submit(function(e) {
            e.preventDefault();

            const email = $('#email').val();
            const password = $('#password').val();
            const passwordConfirmation = $('#password-confirmation').val();

            // Validation
            let isValid = true;
            const errorMessages = [];

            if (!email) {
                isValid = false;
                errorMessages.push('Vui lòng nhập email.');
            } else if (!validateEmail(email)) {
                isValid = false;
                errorMessages.push('Email không hợp lệ.');
            }

            if (!password) {
                isValid = false;
                errorMessages.push('Vui lòng nhập mật khẩu mới.');
            } else if (password.length < 5) {
                isValid = false;
                errorMessages.push('Mật khẩu phải có ít nhất 5 ký tự.');
            }

            if (!passwordConfirmation) {
                isValid = false;
                errorMessages.push('Vui lòng xác nhận mật khẩu.');
            } else if (password !== passwordConfirmation) {
                isValid = false;
                errorMessages.push('Mật khẩu xác nhận không khớp.');
            }

            if (!isValid) {
                alert(errorMessages.join('\n'));
                return;
            }

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
                        window.location.href = URL + '/auth/login';
                    } else if (res.status === 204) {
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