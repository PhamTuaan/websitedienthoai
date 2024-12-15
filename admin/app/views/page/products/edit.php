<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Cập nhật sản phẩm</h5>
                        </div>
                        <a href="<?php echo URL_APP . '/products' ?>" class="btn bg-gradient-primary btn-sm mb-0 d-flex align-items-center" type="button"><i class="fas fa-arrow-left"></i>&nbsp; Trở về</a>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form method="POST" action="<?php echo URL_APP . '/products/update/' . $product['product_id'] ?>" id="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_name" class="form-control-label">Tên sản phẩm</label>
                                    <input class="form-control" type="text" placeholder="product name" value="<?php echo $product['product_name'] ?>" id="product_name" name="product_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Giá (VNĐ)</label>
                                    <input class="form-control" type="number" placeholder="000" value="<?php echo $product['price'] ?>" id="price" name="price">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Danh mục</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <?php foreach ($categories as $index => $category) : ?>
                                            <option value="<?php echo $category['category_id'] ?>" <?php echo $product['category_id'] == $category['category_id'] ? 'selected' : ''  ?>>
                                                <?php echo $category['category_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity" class="form-control-label">Số lượng</label>
                                    <input class="form-control" type="number" placeholder="00" id="quantity" value="<?php echo $product['quantity'] ?>" name="quantity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="form-control-label">Hình ảnh</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" type="file" placeholder="Choose product image" id="image" name="image">
                                        <!-- Display current product image -->
                                        <div class="my-2 bg-white p-2">
                                            <img src="<?php echo IMAGES_PATH . '/' . $product['image'] ?>" alt="Current Product Image" style="width: 200px; height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" rows="3" name="description"><?php echo $product['description'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Cập nhật sản phẩm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/products"

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