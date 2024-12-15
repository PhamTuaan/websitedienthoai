<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Quản lý danh mục</h5>
                        </div>
                        <?php if (hasPermission('create_category')): ?>
                            <a href=" <?php echo URL_APP . '/categories/create'; ?>"
                                class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Thêm danh mục mới</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (isset($_SESSION['success'])): ?>
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
                        <table class="table align-items-center mb-0" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Tên danh mục
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Trạng thái
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ngày tạo
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
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
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/categories";

    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: `${URL_GLOBAL}/all`,
            contentType: false,
            processData: false,
            success: function (res) {
                console.log(res);
                $('#categoriesTable').DataTable({
                    data: res.data,
                    columns: [{
                        data: "category_id",
                        className: "ps-4"
                    },
                    {
                        data: "category_name",
                        render: function (data, type, row) {
                            return '<div><p class="text-xs font-weight-bold mb-0">' + data + '</p></div>';
                        }
                    },
                    {
                        data: "status",
                        className: "text-center",
                        render: function (data, type, row) {
                            if (data == 1) {
                                return '<p class="text-xs font-weight-bold mb-0"><span class="badge bg-primary">Đang hoạt động</span></p>';
                            } else {
                                return '<p class="text-xs font-weight-bold mb-0"><span class="badge bg-warning text-dark">Vô hiệu hóa</span></p>';
                            }
                        }
                    },
                    {
                        data: "created_at",
                        className: "text-center",
                        render: function (data, type, row) {
                            return '<span class="text-secondary text-xs font-weight-bold">' + data + '</span>';
                        }
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function (data, type, row) {
                            var editUrl = '<?php echo URL_APP; ?>/categories/edit/' + row.category_id;
                            var deleteUrl = '<?php echo URL_APP; ?>/categories/destroy/' + row.category_id;
                            var deleteModalId = 'deleteModal' + row.category_id;
                            var editButton = '<?php if (hasPermission('edit_category')): ?><a href="' + editUrl + '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user"><i class="fas fa-user-edit text-secondary"></i></a><?php endif; ?>';
                            var deleteButton = '<?php if (hasPermission('delete_category')): ?><span type="button" data-bs-toggle="modal" data-bs-target="#' + deleteModalId + '">' +
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
                                '<div class="modal-body">' +
                                'Bạn có chắc chắn muốn xóa danh mục ' + row.category_name + '?' +
                                '</div>' +
                                '<div class="modal-footer">' +
                                '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                                '<form method="POST" id="delete_form" action="' + deleteUrl + '">' +
                                '<button type="submit" class="btn ' + (row.status == 1 ? 'btn-danger' : 'btn-success') + '">' + (row.status == 1 ? 'Xóa' : 'Khôi phục') + '</button>' +
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
            error: function (xhr, status, error) {
                showToast('Có lỗi xảy ra: ' + error, false);
            }
        });

        $('#delete_form').submit(function (e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function (res) {
                    showToast(res.message, true);
                    $('#categoriesTable').DataTable().ajax.reload();
                    $('#deleteModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });
    });

    initial()
</script>