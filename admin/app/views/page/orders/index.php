<style>
    .disabled-button {
        pointer-events: none;
        opacity: 0.6;
    }
</style>

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Quản lý đơn hàng</h5>
                        </div>
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
                        <table class="table align-items-center mb-0" id="ordersTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Tên người nhận
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Địa chỉ người nhận
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        SĐT người nhận
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tổng tiền
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Mô tả
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
                                <?php if (count($orders) == 0): ?>
                                    <h2 class="font-weight-bold text-center m-3">Bạn không có đơn hàng nào</h2>
                                <?php else: ?>
                                    <?php foreach ($orders as $index => $order): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $index + 1; ?></p>
                                            </td>
                                            <td>
                                                <div>
                                                    <p class="text-xs text-center font-weight-bold mb-0 text-truncate"
                                                        style="max-width: 130px;"><?php echo $order['name_receiver']; ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <p class="text-xs text-center font-weight-bold mb-0 text-truncate"
                                                        style="max-width: 130px;"><?php echo $order['address_receiver']; ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <p class="text-xs text-center font-weight-bold mb-0">
                                                        <?php echo $order['phone_receiver']; ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <p class="text-xs text-center font-weight-bold mb-0">
                                                        <?php echo $order['total_price']; ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <p class="text-xs text-center font-weight-bold mb-0 text-truncate"
                                                        style="max-width: 130px;"><?php echo $order['notes']; ?></p>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?php
                                                    switch ($order['status']) {
                                                        case 'đang chờ':
                                                            echo '<span class="badge bg-warning text-dark">Đang chờ</span>';
                                                            break;

                                                        case 'đang giao':
                                                            echo '<span class="badge bg-primary text-white">Đang giao</span>';
                                                            break;

                                                        case 'đã giao':
                                                            echo '<span class="badge bg-success">Đã giao</span>';
                                                            break;

                                                        case 'đã hủy':
                                                            echo '<span class="badge bg-danger">Đã hủy</span>';
                                                            break;

                                                        default:
                                                            echo '<span class="badge bg-warning text-dark">Đang chờ</span>';
                                                    }
                                                    ?>

                                                </p>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold"><?php echo $order['created_at']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php if (hasPermission('show_order')): ?>
                                                    <span class="mx-3" data-bs-toggle="modal"
                                                        data-bs-target="#previewModal<?php echo $order['order_id']; ?>">
                                                        <i class="fas fa-search text-secondary cursor-pointer"></i>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (hasPermission('delete_order')): ?>
                                                    <span type="button" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal<?php echo $order['order_id']; ?>"
                                                        class="<?php echo ($order['status'] == 'đã hủy' || $order['status'] == 'đã giao') ? 'disabled-button' : ''; ?>">
                                                        <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                                    </span>
                                                <?php endif; ?>

                                                <div class="modal fade" id="previewModal<?php echo $order['order_id']; ?>"
                                                    tabindex="-1" aria-labelledby="previewModal" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="previewModal">Thông tin chi tiết đơn
                                                                    hàng</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="w-full text-white" style="background: #25a99b;">
                                                                    <span class="d-flex justify-content-between p-5">
                                                                        <span
                                                                            class="text-white fs-5 text-truncate align-items-center">
                                                                            <?php
                                                                            switch ($order['status']) {
                                                                                case 'đang chờ':
                                                                                    echo 'Đang chờ xác nhận';
                                                                                    break;
                                                                                case 'đang giao':
                                                                                    echo 'Đang giao';
                                                                                    break;
                                                                                case 'đã giao':
                                                                                    echo 'Đơn hàng đã giao';
                                                                                    break;
                                                                                case 'đã hủy':
                                                                                    echo 'Đơn hàng đã hủy';
                                                                                    break;
                                                                                default:
                                                                                    echo 'Đang chờ xác nhận';
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                        <span><i
                                                                                class="fas fa-box-open text-white fs-1"></i></span>
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex justify-content-between mt-2">
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <span class="text-secondary">Mã đơn hàng <span
                                                                                class="text-danger">*</span>: </span>
                                                                        <span
                                                                            class="text-dark font-weight-bold"><?php echo $order['order_id'] . $order['created_at']; ?></span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <span class="text-secondary">Trạng thái <span
                                                                                class="text-danger">*</span>: </span>
                                                                        <span class="text-dark font-weight-bold">
                                                                            <?php
                                                                            switch ($order['status']) {
                                                                                case 'đang chờ':
                                                                                    echo '<span class="badge bg-warning text-dark">Đang chờ</span>';
                                                                                    break;
                                                                                case 'đang giao':
                                                                                    echo '<span class="badge bg-primary">Đang giao</span>';
                                                                                    break;
                                                                                case 'đã giao':
                                                                                    echo '<span class="badge bg-success">Đã giao</span>';
                                                                                    break;
                                                                                case 'đã hủy':
                                                                                    echo '<span class="badge bg-danger">Đã hủy</span>';
                                                                                    break;
                                                                                default:
                                                                                    echo '<span class="badge bg-warning text-dark">Đang chờ</span>';
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between">
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <span class="text-secondary">Ngày tạo <span
                                                                                class="text-danger">*</span>: </span>
                                                                        <span
                                                                            class="text-dark font-weight-bold"><?php echo $order['created_at']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <hr class="mb-3">
                                                                <div class="row">
                                                                    <div
                                                                        class="col-lg-4 col-md-6 col-sm-12 d-flex flex-column align-items-start">
                                                                        <span class="text-secondary">Tên người nhận<span
                                                                                class="text-danger">*</span>:</span>
                                                                        <span
                                                                            class="text-dark font-weight-bold"><?php echo $order['name_receiver']; ?></span>
                                                                    </div>
                                                                    <div
                                                                        class="col-lg-4 col-md-6 col-sm-12 d-flex flex-column align-items-center">
                                                                        <span class="text-secondary">Địa chỉ người nhận<span
                                                                                class="text-danger">*</span>:</span>
                                                                        <span
                                                                            class="text-dark font-weight-bold"><?php echo $order['address_receiver']; ?>
                                                                        </span>
                                                                    </div>
                                                                    <div
                                                                        class="col-lg-4 col-md-6 col-sm-12 d-flex flex-column align-items-end text-start">
                                                                        <span class="text-secondary">SĐT người nhận<span
                                                                                class="text-danger">*</span>:</span>
                                                                        <span
                                                                            class="text-dark font-weight-bold"><?php echo $order['phone_receiver']; ?>
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-12 d-flex align-items-center gap-1 mt-2">
                                                                        <span class="text-secondary">Ghi chú<span
                                                                                class="text-danger">*</span>:</span>
                                                                        <span
                                                                            class="text-dark font-weight-bold"><?php echo $order['notes']; ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="d-flex align-items-center gap-1">
                                                                    <span class="text-secondary">Tổng số lượng sản phẩm <span
                                                                            class="text-danger">*</span>: </span>
                                                                    <span class="text-dark font-weight-bold">
                                                                        <?php echo count($order['orderDetail']); ?>
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="table-responsive p-0">
                                                                        <table class="table align-items-center mb-0">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th
                                                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                                        ID
                                                                                    </th>
                                                                                    <th
                                                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                                        Tên sản phẩm
                                                                                    </th>
                                                                                    <th
                                                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                                        Hình ảnh
                                                                                    </th>
                                                                                    <th
                                                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                                        Giá thành
                                                                                    </th>
                                                                                    <th
                                                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                                        Số lượng
                                                                                    </th>
                                                                                    <th
                                                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                                        Danh mục
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($order['orderDetail'] as $detail): ?>
                                                                                    <tr>
                                                                                        <td class="ps-4">
                                                                                            <p
                                                                                                class="text-xs font-weight-bold mb-0">
                                                                                                <?php echo $detail['order_detail_id']; ?>
                                                                                            </p>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div>
                                                                                                <p
                                                                                                    class="text-xs font-weight-bold mb-0">
                                                                                                    <?php echo $detail['product']['product_name']; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class="d-flex justify-content-center">
                                                                                            <img src="<?php echo IMAGES_PATH . '/' . $detail['product']['image']; ?>"
                                                                                                alt="Product Image"
                                                                                                class="img-responsive avatar avatar-md me-3">
                                                                                        </td>
                                                                                        <td>
                                                                                            <div>
                                                                                                <p
                                                                                                    class="text-xs font-weight-bold mb-0">
                                                                                                    <?php echo number_format($detail['product']['price'], 0, '', ','); ?>
                                                                                                </p>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div>
                                                                                                <p
                                                                                                    class="text-xs font-weight-bold mb-0">
                                                                                                    <?php echo $detail['quantity']; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div>
                                                                                                <p
                                                                                                    class="text-xs font-weight-bold mb-0">
                                                                                                    <?php echo $detail['product']['categories']['category_name']; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer d-flex justify-content-between">
                                                                <span class="text-secondary fs-5">
                                                                    Tổng tiền: <strong>
                                                                        <?php echo number_format($order['total_price'], 0, '', ','); ?></strong>
                                                                    VNĐ.
                                                                </span>
                                                                <div class="d-flex gap-2">
                                                                    <button type="button" class="btn btn-outline-primary"
                                                                        data-bs-dismiss="modal">Trở về</button>
                                                                    <?php if (hasPermission('confirm_order')): ?>
                                                                        <form method="POST" id="update_form"
                                                                            action="<?php echo URL_APP . '/orders/updateOrder' . '/' . $order['order_id']; ?>">
                                                                            <button type="submit" class="btn btn-primary" <?php echo ($order['status'] == 'đã hủy' || $order['status'] == 'đã giao' || $order['status'] == 'đang giao') ? 'disabled' : null; ?>>Xác nhận đơn hàng</button>
                                                                        </form>
                                                                    <?php endif; ?>

                                                                    <?php if ($order['status'] === 'đang giao'): ?>
                                                                        <!-- Nút Xác nhận đã giao hàng -->
                                                                        <form method="POST" id="confirm_delivery_form<?php echo $order['order_id']; ?>" action="<?php echo URL_APP . '/orders/confirmDelivery/' . $order['order_id']; ?>">
                                                                            <button type="button" class="btn btn-success confirm-delivery-btn" data-order-id="<?php echo $order['order_id']; ?>">Xác nhận đã giao hàng</button>
                                                                        </form>
                                                                    <?php endif; ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal<?php echo $order['order_id']; ?>"
                                                    tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Xác nhận hủy đơn
                                                                    hàng</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Bạn có chắc chắn muốn hủy đơn hàng:
                                                                <?php echo $order['order_id'] . '.' . $order['created_at']; ?>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Trở về</button>
                                                                <form method="POST" id="delete_form"
                                                                    action="<?php echo URL_APP . '/orders/destroy' . '/' . $order['order_id']; ?>">
                                                                    <button type="submit" class="btn btn-danger">Hủy đơn
                                                                        hàng</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new DataTable('#ordersTable', {
        formatNumber: function (toFormat) {
            return toFormat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'");
        }
    });

    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/orders";
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: `${URL_GLOBAL}/all`,
            contentType: false,
            processData: false,
            success: function (res) {
                console.log(res);
            }
        });

        $('#update_form').submit(function (e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');

            console.log('RUN?');

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function (res) {
                    if (res.status === 200) {
                        $('#deleteModal').modal('hide');
                        showToast(res.message, true);
                        window.location.reload();
                    }

                    if (res.status === 404) {
                        showToast(res.message, false);
                        return;
                    }
                },
                error: function (xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
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
                    if (res.status === 404) {
                        showToast(res.message, false);
                        return;
                    }

                    if (res.status === 204) {
                        $('#deleteModal').modal('hide');
                        showToast(res.message, true);
                        window.location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });

        $('.confirm-delivery-btn').click(function () {
            var orderId = $(this).data('order-id');
            var form = $('#confirm_delivery_form' + orderId);
            var url = form.attr('action');

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function (res) {
                    if (res.status === 200) {
                        showToast(res.message, true); // Hiển thị thông báo thành công
                        window.location.reload(); // Tải lại trang
                    } else {
                        showToast(res.message, false); // Hiển thị thông báo lỗi
                    }
                },
                error: function (xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });

    });
</script>
