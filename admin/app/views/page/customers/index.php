<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Quản lý khách hàng</h5>
                        </div>
                        <?php if (hasPermission('create_customer')) : ?>
                            <a href=" <?php echo URL_APP . '/customers/create' ?>" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp;Thêm khách hàng mới</a>
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
                        <table class="table align-items-center mb-0" id="customersTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Tên khách hàng
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Email
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ngày sinh
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Địa chỉ
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Số điện thoại
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Trạng thái
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ngày tạo
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
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/customers";
    const URL_APP = "http://localhost/phone-ecommerce-chat/admin";

    const initial = () => {
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: `${URL_GLOBAL}/all`,
                contentType: false,
                processData: false,
                success: function(res) {
                    console.log(res);
                    $('#customersTable').DataTable({
                        data: res.data,
                        columns: [{
                                data: null,
                                render: function(data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                data: "customer_name",
                                render: function(data, type, row) {
                                    return '<div><p class="text-xs font-weight-bold mb-0 text-truncate" style="max-width: 100px;">' + data + '</p></div>';
                                }
                            },
                            {
                                data: "email",
                                render: function(data, type, row) {
                                    return '<div><p class="text-xs font-weight-bold mb-0 text-truncate" style="max-width: 100px;">' + data + '</p></div>';
                                }
                            },
                            {
                                data: "birthday",
                                render: function(data, type, row) {
                                    return '<div><p class="text-xs font-weight-bold mb-0 text-truncate" style="max-width: 100px;">' + data + '</p></div>';
                                }
                            },
                            {
                                data: "address",
                                render: function(data, type, row) {
                                    return '<div><p class="text-xs font-weight-bold mb-0 text-truncate" style="max-width: 100px;">' + data + '</p></div>';
                                }
                            },
                            {
                                data: "phone",
                                render: function(data, type, row) {
                                    return '<div><p class="text-xs font-weight-bold mb-0 text-truncate" style="max-width: 100px;">' + data + '</p></div>';
                                }
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
                                    var editUrl = URL_APP + '/customers/edit/' + row.customer_id;
                                    var deleteUrl = URL_APP + '/customers/destroy/' + row.customer_id;
                                    var deleteModalId = 'deleteModal' + row.customer_id;
                                    var editButton = '<?php if (hasPermission('edit_customer')) : ?><a href="' + editUrl + '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit customer">' +
                                        '<i class="fas fa-user-edit text-secondary"></i>' +
                                        '</a><?php endif; ?>';
                                    var deleteButton = '<?php if (hasPermission('delete_customer')) : ?><span type="button" data-bs-toggle="modal" data-bs-target="#' + deleteModalId + '">' +
                                        (row.status == 0 ? '<i class="fas fa-undo text-secondary cursor-pointer"></i>' : '<i class="cursor-pointer fas fa-trash text-secondary"></i>') +
                                        '</span><?php endif; ?>';
                                    return editButton + deleteButton +
                                    '<div class="modal fade" id="' + deleteModalId + '" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">' +
                                    '<div class="modal-dialog">' +
                                    '<div class="modal-content">' +
                                    '<div class="modal-header">' +
                                    '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                    '</div>' +
                                    '<div class="modal-body">' +
                                    (row.status == 1 ? 'Bạn có chắc chắn muốn xóa khách hàng: ' + row.customer_name + '?' : 'Bạn có chắc chắn muốn khôi phục khách hàng: ' + row.customer_name + '?') +
                                    '<div class="modal-footer">' +
                                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở về</button>' +
                                    '<form method="POST" id="delete_form" action="' + deleteUrl + '">' +
                                    '<button type="submit" class="btn ' + (row.status == 1 ? 'btn-danger' : 'btn-success') + '">' +
                                    (row.status == 1 ? 'Xóa khách hàng' : 'Khôi phục') +
                                    '</button>' +
                                    '</form>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</td>';
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
                            $('#customersTable').DataTable().ajax.reload();
                            $('#deleteModal').modal('hide');
                            window.location.href = "http://localhost/phone-ecommerce-chat/admin/customers";
                            showToast(res.message, true);
                        }
                    },
                    error: function(xhr, status, error) {
                        showToast('Có lỗi xảy ra: ' + error, false);
                    }
                });
            });
        });
    }

    initial()
</script>