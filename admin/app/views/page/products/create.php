<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Thêm mới sản phẩm</h5>
                        </div>
                        <a href="<?php echo URL_APP . '/products/index' ?>" class="btn bg-gradient-primary btn-sm mb-0 d-flex align-items-center" type="button"><i class="fas fa-arrow-left"></i>&nbsp; Trở về</a>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form method="POST" action="<?php echo URL_APP . '/products/store' ?>" id="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_name" class="form-control-label">Tên sản phẩm</label>
                                    <input class="form-control" type="text" placeholder="product name" id="product_name" name="product_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Giá (VNĐ)</label>
                                    <input class="form-control" type="number" placeholder="000" id="price" name="price">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Danh mục</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <?php foreach ($categories as $index => $category) : ?>
                                            <option value="<?php echo $category['category_id'] ?>">
                                                <?php echo $category['category_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity" class="form-control-label">Số lượng</label>
                                    <input class="form-control" type="number" placeholder="00" id="quantity" name="quantity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="form-control-label">Hình ảnh</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="file" placeholder="Choose product image" id="image" name="image">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" rows="3" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Thêm sản phẩm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/products"

    /**
     * Khởi tạo sự kiện khi tài liệu được tải xong.
     * Khi form được submit, hành động mặc định sẽ bị ngăn chặn để thực hiện gửi form bằng AJAX.
     * 
     * - `e.preventDefault()`: Ngăn không cho form thực hiện submit theo cách thông thường.
     * - `FormData(this)`: Tạo đối tượng FormData từ form hiện tại để gửi dữ liệu dạng multipart/form-data.
     * - `console.log(formData)`: In ra console dữ liệu form để kiểm tra.
     * - `$.ajax`: Thực hiện gửi yêu cầu AJAX đến server.
     *   - `type: 'POST'`: Phương thức gửi dữ liệu là POST.
     *   - `url: $(this).attr('action')`: URL được lấy từ thuộc tính 'action' của form.
     *   - `data: formData`: Dữ liệu gửi đi là đối tượng FormData.
     *   - `contentType: false`: Không set contentType mặc định, để jQuery tự định nghĩa.
     *   - `processData: false`: Không xử lý dữ liệu trước khi gửi đi, phù hợp với dữ liệu dạng file.
     *   - `success`: Hàm được gọi khi có phản hồi thành công từ server.
     *     - `showToast(res.message, true)`: Hiển thị thông báo thành công.
     *     - `window.location.href = URL_GLOBAL`: Chuyển hướng đến URL_GLOBAL (đã bị comment).
     *   - `error`: Hàm được gọi khi có lỗi xảy ra trong quá trình gửi yêu cầu.
     *     - `showToast('Có lỗi xảy ra: ' + error, false)`: Hiển thị thông báo lỗi.
     */
    $(document).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault();

            if (!validateForm()) {
                showToast('Vui lòng kiểm tra lại thông tin nhập.', false);
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
                    showToast(res.message, true);
                    window.location.href = URL_GLOBAL;
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });

        function validateForm() {
            let isValid = true;
            const productName = $('#product_name').val().trim();
            const price = $('#price').val().trim();
            const quantity = $('#quantity').val().trim();
            const description = $('#description').val().trim();

            // Validate product name
            if (productName === '') {
                $('#product_name').addClass('border-danger');
                isValid = false;
            } else {
                $('#product_name').removeClass('border-danger');

            }

            // Image
            // Validate image
            const imageInput = $('#image')[0];
            if (imageInput.files.length === 0) {
                $('#image').addClass('border-danger');
                showToast('Vui lòng chọn hình ảnh cho sản phẩm.', false);
                isValid = false;
            } else {
                const file = imageInput.files[0];
                const fileType = file.type;
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validImageTypes.includes(fileType)) {
                    $('#image').addClass('border-danger');
                    showToast('Chỉ chấp nhận hình ảnh định dạng JPEG, PNG, hoặc GIF.', false);
                    isValid = false;
                } else {
                    $('#image').removeClass('border-danger');
                }
            }

            // Validate price
            if (price === '' || isNaN(price) || parseFloat(price) <= 0) {
                $('#price').addClass('border-danger');
                isValid = false;
            } else {
                $('#price').removeClass('border-danger');

            }

            // Validate quantity
            if (quantity === '' || isNaN(quantity) || parseInt(quantity) <= 0) {
                $('#quantity').addClass('border-danger');
                isValid = false;
            } else {
                $('#quantity').removeClass('border-danger');

            }

            // Validate description
            if (description === '') {
                $('#description').addClass('border-danger');
                isValid = false;
            } else {
                $('#description').removeClass('border-danger');

            }

            return isValid;
        }
    });
</script>