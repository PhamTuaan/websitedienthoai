<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Quản lý sản phẩm</h5>
                        </div>
                        <?php if (hasPermission('create_product')) : ?>
                            <a href=" <?php echo URL_APP . '/products/create' ?>" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Thêm sản phẩm mới</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="m-3 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                        <span class="alert-text text-white">
                            <?php echo $_SESSION['success']; ?></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?php
                    unset($_SESSION['success']);
                    ?>
                <?php endif; ?>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <table class="table align-items-center mb-0" id="productsTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Tên sản phẩm
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Hình ảnh
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Giá
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Số lượng
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Mô tả
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Danh mục
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Trạng thái
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ngày tạo
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Thao tác
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Các biến URL được sử dụng:
     * - `URL_GLOBAL`: URL chính để thực hiện các thao tác liên quan đến sản phẩm.
     * - `URL_APP`: URL gốc của ứng dụng.
     * - `IMAGES_PATH`: Đường dẫn tới thư mục chứa hình ảnh sản phẩm.
     */
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/products";
    const URL_APP = "http://localhost/phone-ecommerce-chat/admin";
    const IMAGES_PATH = "http://localhost/phone-ecommerce-chat/storages/public";

    // Sử dụng jQuery để thực hiện gọi AJAX đến server lấy danh sách sản phẩm khi trang được tải xong.
    // Cấu hình DataTables với các cột dữ liệu như ID, tên sản phẩm, hình ảnh, giá, số lượng, mô tả, danh mục, 
    // trạng thái, ngày tạo và các thao tác có thể thực hiện.
    const initial = () => {
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: `${URL_GLOBAL}/all`,
                contentType: false,
                processData: false,
                success: function(res) {
                    console.log(res.data);
                    $('#productsTable').DataTable({
                        data: res.data,
                        columns: [{
                                data: null,
                                render: function(data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                data: "product_name",
                                render: function(data, type, row) {
                                    return '<div><p class="text-xs text-center font-weight-bold mb-0">' + data + '</p></div>';
                                }
                            },
                            {
                                data: "image",
                                render: function(data, type, row) {
                                    return '<td class="d-flex justify-content-center"><img src="' + IMAGES_PATH + '/' + data + '" alt="Product Image" class="img-responsive avatar avatar-md me-3"></td>';
                                }
                            },
                            {
                                data: "price"
                            },
                            {
                                data: "quantity"
                            },
                            {
                                data: "description",
                                render: function(data, type, row) {
                                    return '<div><p class="text-xs text-center font-weight-bold mb-0 text-truncate" style="max-width: 100px;">' + data + '</p></div>';
                                }
                            },
                            {
                                data: "category_name"
                            },
                            {
                                data: "status",
                                render: function(data, type, row) {
                                    var badge = data == "1" ? '<span class="badge bg-primary">Đang hoạt động</span>' : '<span class="badge bg-warning text-dark">Vô hiệu hóa</span>';
                                    return '<p class="text-xs font-weight-bold mb-0">' + badge + '</p>';
                                }
                            },
                            {
                                data: "created_at"
                            },
                            {
                                data: null,
                                render: function(data, type, row) {
                                    var editUrl = URL_APP + '/products/edit/' + row.product_id;
                                    var deleteUrl = URL_APP + '/products/destroy/' + row.product_id;
                                    var deleteModalId = 'deleteModal' + row.product_id;
                                    
                                    var editButton = '<?php if (hasPermission('edit_user')) : ?><a href="' + editUrl + '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user"><i class="fas fa-user-edit text-secondary"></i></a><?php endif; ?>';
                                    var deleteButton = '<?php if (hasPermission('delete_user')) : ?><span type="button" data-bs-toggle="modal" data-bs-target="#' + deleteModalId + '">' +
                                    (row.status == 0 ? '<i class="fas fa-undo text-secondary cursor-pointer"></i>' : '<i class="cursor-pointer fas fa-trash text-secondary"></i>') +
                                    '</span><?php endif; ?>';
                                    return editButton + deleteButton +
                                    '<div class="modal fade" id="' + deleteModalId + '" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">' +
                                    '<div class="modal-dialog">' +
                                    '<div class="modal-content">' +
                                    '<div class="modal-header">' +
                                    '<h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>' +
                                    '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                    '</div>' +
                                    '<div class="modal-body">Bạn có chắc chắn muốn ' + (row.status == 1 ? 'xóa' : 'khôi phục') + ' sản phẩm: ' + row.product_name + '?</div>' +
                                    '<div class="modal-footer">' +
                                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở về</button>' +
                                    '<form method="POST" id="delete_form" action="' + deleteUrl + '">' +
                                    '<button type="submit" class="btn ' + (row.status == 1 ? 'btn-danger' : 'btn-success') + '">' + (row.status == 1 ? 'Xóa sản phẩm' : 'Khôi phục') + '</button>' +
                                    '</form>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                }
                            }
                        ],
                        "aaSorting": []
                    });
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });

            //  Xử lý sự kiện submit của form xóa sản phẩm, gửi yêu cầu POST đến server và cập nhật lại bảng nếu thành công.
            $('#delete_form').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === 404) {
                            showToast(res.message, false);
                            return;
                        }
                        if (res.status === 204) {
                            $('#productsTable').DataTable().ajax.reload();
                            $('#deleteModal').modal('hide');
                            showToast(res.message, true);
                            window.location.href = "http://localhost/phone-ecommerce-chat/admin/products";
                        }
                    },
                    error: function(xhr, status, error) {
                        showToast('Có lỗi xảy ra: ' + error, false);
                    }
                });
            });
        });
    }

    // Gọi hàm initial
    initial()
</script>