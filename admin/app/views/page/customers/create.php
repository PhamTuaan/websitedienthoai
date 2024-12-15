<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Tạo mới khách hàng</h5>
                        </div>
                        <a href="<?php echo URL_APP . '/customers'; ?>" class="btn bg-gradient-primary btn-sm mb-0 d-flex align-items-center" type="button"><i class="fas fa-arrow-left"></i>&nbsp; Trở về</a>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form method="POST" action="<?php echo URL_APP . '/customers/store'; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_name" class="form-control-label">Tên khách hàng</label>
                                    <div class="<?php if (isset($_SESSION['customer_name'])) echo 'border border-danger rounded-3'; ?>">
                                        <input class="form-control" type="text" placeholder="Tên khách hàng" id="customer_name" name="customer_name">
                                        <?php if (isset($_SESSION['customer_name'])) : ?>
                                            <p class="text-danger text-xs mt-2"><?php echo $_SESSION['customer_name']; ?></p>
                                            <?php unset($_SESSION['customer_name']); // Remove the message after displaying it 
                                            ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email</label>
                                    <input class="form-control" type="email" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Mật khẩu</label>
                                    <input class="form-control" type="password" id="password" name="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birthday" class="form-control-label">Ngày sinh</label>
                                    <input class="form-control" type="date" id="birthday" name="birthday">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="form-control-label">Địa chỉ</label>
                                    <input class="form-control" type="text" id="address" name="address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">Số điện thoại</label>
                                    <input class="form-control" type="text" id="phone" name="phone">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Thêm khách hàng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/customers"

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
                        window.location.href = URL_GLOBAL;
                    } else showToast(res.message, false)
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });
    });
</script>