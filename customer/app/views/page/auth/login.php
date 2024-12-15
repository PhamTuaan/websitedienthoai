<main class="main-content my-5">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-5 d-flex flex-column mx-auto">
                        <div class="card border-0 p-3 mt-8">
                            <div class="card-header border-0 pb-0 text-left bg-transparent">
                                <h3 class="font-weight-bolder text-center my-3">Chào mừng đã trở lại</h3>
                                <p class="mb-0 font-weight-normal">Đăng nhập với tài khoản của bạn.</p>
                            </div>
                            <div class="card-body border-0 contact__form">
                                <form role="form" method="POST" action=" <?php echo URL_APP . '/auth/signIn' ?>">
                                    <!-- CSRF Token -->
                                    <label>Email</label>
                                    <div class="mb-3">
                                        <input type="email" name="email" id="email" placeholder="Nhập email của bản tại đây" value="" aria-label="email" aria-describedby="email-addon" required>
                                    </div>
                                    <label>Password</label>
                                    <div>
                                        <input type="password" name="password" id="password" placeholder="Password" value="" aria-label="Password" aria-describedby="password-addon" required>
                                    </div>
                                    <!--  -->
                                    <div class="form-check form-switch mb-5">
                                        <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe" checked style="width: 15px; height: 15px;">
                                        <label class="form-check-label ml-2" for="rememberMe">Ghi nhớ</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark w-100 mt-4 mb-0">Đăng nhập</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer border-0 text-center bg-white pt-0 px-lg-2 px-1">
                                <p class="mb-4 text-sm mx-auto">
                                    Không có tài khoản?
                                    <a href="register" class="text-info text-dark font-weight-bold">Đăng ký</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    const URL = "http://localhost/phone-ecommerce-chat/customer"

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
                success: async function(res) {
                    if (res.status === 200) {
                        showToast(res.message, true);
                        await createConversation(res.data);
                    } else if (res.status === 204) {
                        showToast(res.message, false);
                    } else if (res.status === 404) {
                        showToast(res.message, false);
                    }
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });
    });

    const createConversation = async (data) => {
        await $.ajax({
            type: 'POST',
            url: "http://localhost/phone-ecommerce-chat/admin/conversation/storeConversationByCustomer/" + data.customer_id,
            success: function(res) {
                if (res.status === 200) {
                    console.log(res.message);
                }

                window.location.href = URL + '/home';
            },
            error: function(xhr, status, error) {
                showToast('Có lỗi xảy ra: ' + error, false);
            }
        });
    }
</script>