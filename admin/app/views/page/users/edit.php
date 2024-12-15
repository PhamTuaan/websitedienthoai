<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Cập nhật nhân viên</h5>
                        </div>
                        <a href="<?php echo URL_APP . '/users'; ?>" class="btn bg-gradient-primary btn-sm mb-0 d-flex align-items-center" type="button"><i class="fas fa-arrow-left"></i>&nbsp; Trở về</a>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form method="POST" action="<?php echo URL_APP . '/users/update/' . $user['user_id'] ; ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="fullname" class="form-control-label">Tên nhân viên</label>
                                    <div class="<?php if (isset($_SESSION['fullname'])) echo 'border border-danger rounded-3'; ?>">
                                        <input class="form-control" type="text" value="<?php echo $user['fullname'] ?>" placeholder="Tên nhân viên" id="fullname" name="fullname">
                                        <?php if (isset($_SESSION['fullname'])) : ?>
                                            <p class="text-danger text-xs mt-2"><?php echo $_SESSION['fullname']; ?></p>
                                            <?php unset($_SESSION['fullname']);
                                            ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email</label>
                                    <input class="form-control" type="email" value="<?php echo $user['email'] ?>" placeholder="abc@gmail.com" id="email" name="email" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Mật khẩu</label>
                                    <input class="form-control" type="password" placeholder="" id="password" name="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="form-control-label">Địa chỉ</label>
                                    <input class="form-control" type="input" value="<?php echo $user['address'] ?>" placeholder="TP.HCM" id="address" name="address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">SĐT</label>
                                    <input class="form-control" type="number" value="<?php echo $user['phone'] ?>" placeholder="097..." name="phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender" class="form-control-label">Giới tính</label>
                                    <div>
                                        <input type="radio" id="gender_male" name="gender" <?php echo $user['gender'] == 1 ? 'checked' : '' ?> value="1">
                                        <label for="gender_male">Nam</label><br>
                                        <input type="radio" id="gender_female" name="gender" <?php echo $user['gender'] == 0 ? 'checked' : '' ?> value="0">
                                        <label for="gender_female">Nữ</label><br>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="role" class="form-control-label">Phân quyền</label>
                                    <select name="role" id="role" class="form-control">
                                        <?php foreach ($roles as $role) : ?>
                                            <option value="<?php echo $role['role_id']; ?>" <?php if ($user['role_id'] == $role['role_id']) echo 'selected'; ?>><?php echo $role['role_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                         
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Cập nhật nhân viên</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/users"

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
                    console.log(res);
                    if (res.status === 200) {
                        showToast(res.message, true);
                        window.location.href = URL_GLOBAL;
                    } else {
                        showToast(res.message, false);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('error:' + error);
                    showToast('Lỗi:' + error, false);
                }
            });
        });
    });
</script>