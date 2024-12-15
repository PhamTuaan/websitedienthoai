<style>
    .disabled-button {
        pointer-events: none;
        opacity: 0.6;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">Quản lý nhân viên</h5>
                    </div>
                    <?php if (hasPermission('create_user')) : ?>
                        <a href="<?php echo URL_APP . '/users/create' ?>" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Thêm nhân viên mới</a>
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
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-4">
                    <table class="table align-items-center mb-0" id="usersTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Họ và tên</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Địa chỉ</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SĐT</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Giới tính</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phân quyền</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ngày tạo</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng thái</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thao tác</th>
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

<script>
const URL_GLOBAL = "<?php echo URL_APP; ?>/users";

const initial = () => {
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 100);

        $('.btn-close').click(function() {
            $(this).closest('.alert').remove();
        });

        $.ajax({
            type: 'GET',
            url: `${URL_GLOBAL}/all`,
            contentType: false,
            processData: false,
            success: function(res) {
                $('#usersTable').DataTable({
                    data: res.data,
                    columns: [
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: "fullname",
                            render: function(data) {
                                return `<div><p class="text-xs font-weight-bold mb-0">${data || ''}</p></div>`;
                            }
                        },
                        {
                            data: "email",
                            render: function(data) {
                                return `<div><p class="text-xs font-weight-bold mb-0">${data || ''}</p></div>`;
                            }
                        },
                        {
                            data: "address",
                            render: function(data) {
                                return `<div><p class="text-xs font-weight-bold mb-0">${data || 'N/A'}</p></div>`;
                            }
                        },
                        {
                            data: "phone",
                            render: function(data) {
                                return `<div><p class="text-xs font-weight-bold mb-0">${data || 'N/A'}</p></div>`;
                            }
                        },
                        {
                            data: "gender",
                            render: function(data) {
                                return `<div><p class="text-xs font-weight-bold mb-0">${parseInt(data) === 1 ? 'Nam' : 'Nữ'}</p></div>`;
                            }
                        },
                        {
                            data: "role_name",
                            render: function(data) {
                                return `<div><p class="text-xs font-weight-bold mb-0">${data || ''}</p></div>`;
                            }
                        },
                        {
                            data: "created_at",
                            render: function(data) {
                                return `<p class="text-xs text-center font-weight-bold mb-0">${data || ''}</p>`;
                            }
                        },
                        {
                            data: "status",
                            render: function(data) {
                                const badge = parseInt(data) === 1 ? 
                                    '<span class="badge bg-primary">Đang hoạt động</span>' : 
                                    '<span class="badge bg-warning text-dark">Vô hiệu hóa</span>';
                                return `<div class="text-center">${badge}</div>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                const editUrl = `${URL_GLOBAL}/edit/${row.user_id}`;
                                const deleteModalId = `deleteModal${row.user_id}`;
                                const isActive = parseInt(row.status) === 1;
                                
                                const editButton = `
                                    <?php if (hasPermission('edit_user')) : ?>
                                    <a href="${editUrl}" class="mx-3 ${!isActive ? 'disabled-button' : ''}">
                                        <i class="fas fa-user-edit text-secondary"></i>
                                    </a>
                                    <?php endif; ?>`;

                                const icon = isActive ? 
                                    '<i class="cursor-pointer fas fa-ban text-danger"></i>' : 
                                    '<i class="cursor-pointer fas fa-undo text-success"></i>';

                                return `
                                    ${editButton}
                                    <?php if (hasPermission('delete_user')) : ?>
                                    <span type="button" data-bs-toggle="modal" data-bs-target="#${deleteModalId}">
                                        ${icon}
                                    </span>
                                    <div class="modal fade" id="${deleteModalId}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">${isActive ? 'Vô hiệu hóa' : 'Kích hoạt'} nhân viên</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn có chắc chắn muốn ${isActive ? 'vô hiệu hóa' : 'kích hoạt'} nhân viên: ${row.fullname}?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở về</button>
                                                    <button type="button" class="btn ${isActive ? 'btn-danger' : 'btn-success'} submit-status-change" 
                                                        data-id="${row.user_id}" 
                                                        data-status="${row.status}"
                                                        data-action="${isActive ? 'disable' : 'restore'}">
                                                        ${isActive ? 'Vô hiệu hóa' : 'Kích hoạt'} nhân viên
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>`;
                            }
                        }
                    ],
                    
                });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
}

$(document).on('click', '.submit-status-change', function(e) {
    e.preventDefault();
    const userId = $(this).data('id');
    const action = $(this).data('action');
    const modal = $(this).closest('.modal');
    
    $.ajax({
        url: `${URL_GLOBAL}/${action}/${userId}`,
        type: 'POST',
        success: function(response) {
            if (response.status === 200) {
                modal.modal('hide');
                
                const alertHtml = `
                    <div class="m-3 alert alert-success alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">${response.message}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>`;
                $('.card-header').after(alertHtml);
                
                setTimeout(function() {
                    $(".alert").fadeTo(500, 0).slideUp(500, function() {
                        $(this).remove();
                    });
                }, 100);

                const table = $('#usersTable').DataTable();
                const row = table.row($(`#deleteModal${userId}`).closest('tr'));
                const rowData = row.data();
                
                rowData.status = rowData.status === "1" ? "0" : "1";
                
                row.data(rowData).draw(false);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});

initial();
</script>