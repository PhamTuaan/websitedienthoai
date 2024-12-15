<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">Quản lý đánh giá sản phẩm</h5>
                    </div>
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
                    <table class="table align-items-center mb-0" id="reviewsTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nội dung đánh giá</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email người dùng</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Đánh giá sao</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng thái</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ngày tạo</th>
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
const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/product_review";

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
                $('#reviewsTable').DataTable({
                    data: res.data,
                    columns: [
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: "content",
                            render: function(data) {
                                return `<div><p class="text-xs font-weight-bold mb-0">${data || ''}</p></div>`;
                            }
                        },
                        {
                            data: "email",
                            render: function(data) {
                                return `<div><p class="text-xs font-weight-bold mb-0">${data || 'N/A'}</p></div>`;
                            }
                        },
                        {
                            data: "rate",
                            render: function(data) {
                                let stars = '';
                                for(let i = 0; i < data; i++) {
                                    stars += '<i class="fas fa-star text-warning"></i>';
                                }
                                for(let i = data; i < 5; i++) {
                                    stars += '<i class="far fa-star text-warning"></i>';
                                }
                                return `<div class="text-center">${stars} <span>(${data || 0})</span></div>`;
                            }
                        },
                        {
                            data: "status",
                            render: function(data) {
                                const badge = parseInt(data) === 1 ? 
                                    '<span class="badge bg-primary">Hiển thị</span>' : 
                                    '<span class="badge bg-warning text-dark">Ẩn</span>';
                                return `<p class="text-xs font-weight-bold mb-0">${badge}</p>`;
                            }
                        },
                        {
                            data: "created_at",
                            render: function(data) {
                                return `<p class="text-xs text-center font-weight-bold mb-0">${data || ''}</p>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                const deleteModalId = `deleteModal${row.review_id}`;
                                const deleteUrl = `${URL_GLOBAL}/destroy/${row.review_id}`;
                                
                                const icon = row.status == 1 ? 
                                    '<i class="cursor-pointer fas fa-trash text-danger"></i>' : 
                                    '<i class="cursor-pointer fas fa-undo text-success"></i>';
                                    
                                const modalTitle = row.status == 1 ? 'Xác nhận ẩn' : 'Xác nhận khôi phục';
                                const actionText = row.status == 1 ? 'ẩn' : 'khôi phục';
                                const buttonClass = row.status == 1 ? 'btn-danger' : 'btn-success';
                                const buttonText = row.status == 1 ? 'Ẩn đánh giá' : 'Khôi phục đánh giá';

                                return `
                                    <?php if (hasPermission('delete_product_review')) : ?>
                                    <span type="button" data-bs-toggle="modal" data-bs-target="#${deleteModalId}">
                                        ${icon}
                                    </span>
                                    <div class="modal fade" id="${deleteModalId}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">${modalTitle}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn có chắc chắn muốn ${actionText} đánh giá này?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở về</button>
                                                    <form method="POST" action="${deleteUrl}">
                                                        <button type="submit" class="btn ${buttonClass}">
                                                            ${buttonText}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>`;
                            }
                        }
                    ],
                    
                    "aaSorting": []
                });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
}

initial();
</script>