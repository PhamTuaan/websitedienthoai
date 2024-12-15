<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Tạo mới danh mục</h5>
                        </div>
                        <a href="<?php echo URL_APP . '/categories/index'; ?>" class="btn bg-gradient-primary btn-sm mb-0 d-flex align-items-center" type="button"><i class="fas fa-arrow-left"></i>&nbsp; Trở về</a>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form method="POST" action="<?php echo URL_APP . '/categories/store'; ?>" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_name" class="form-control-label">Tên danh mục</label>
                                <div class="<?php if (isset($_SESSION['category_name'])) echo 'border border-danger rounded-3'; ?>">
                                    <input class="form-control" type="text" placeholder="category name" id="category_name" name="category_name">
                                    <?php if (isset($_SESSION['category_name'])) : ?>
                                        <p class="text-danger text-xs mt-2"><?php echo $_SESSION['category_name']; ?></p>
                                        <?php unset($_SESSION['category_name']); // Remove the message after displaying it 
                                        ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-primary">Thêm danh mục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/categories"

    $(document).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            console.log(formData);

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    showToast(res.message, true);
                    window.location.href = URL_GLOBAL + '/index'
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });
    });
</script>