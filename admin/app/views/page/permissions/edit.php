<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Cập nhật quyền</h5>
                        </div>
                        <a href="<?php echo URL_APP . '/permission/index'; ?>" class="btn bg-gradient-primary btn-sm mb-0 d-flex align-items-center" type="button"><i class="fas fa-arrow-left"></i>&nbsp; Trở về</a>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form method="POST" action="<?php echo URL_APP . '/permission/update/' . $role['role_id']; ?>" id="form" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role_name" class="form-control-label">Tên quyền</label>
                                <div class="<?php if (isset($_SESSION['role_name'])) echo 'border border-danger rounded-3'; ?>">
                                    <input class="form-control" type="text" value="<?php echo $role['role_name']; ?>" placeholder="Nhập tên quyền" id="role_name" name="permission_name" required>
                                    <?php if (isset($_SESSION['role_name'])) : ?>
                                        <p class="text-danger text-xs mt-2"><?php echo $_SESSION['role_name']; ?></p>
                                        <?php unset($_SESSION['role_name']); 
                                        ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="permission_name" class="form-control-label">
                                    Thêm quyền
                                </label>
                                <div>
                                    <?php 
                                        foreach ($permissions as $permission) {
                                            $checked = in_array($permission['permission_id'], $permission_ids) ? 'checked' : '';
                                            echo '<label class="pl-2 d-block">';
                                            echo '<input type="checkbox" name="functions[]" value="' . $permission['permission_id'] . '" ' . $checked . '>';
                                            echo $permission['permission_name'];
                                            echo '</label>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-primary">Cập nhật quyền</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/permission"

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
                    showToast(res.message, true);
                    window.location.href = `${URL_GLOBAL}/`
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });
    });
</script>