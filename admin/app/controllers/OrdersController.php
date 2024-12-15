<?php
/**
 * OrdersController quản lý các thao tác liên quan đến đơn hàng
 * bao gồm hiển thị danh sách đơn hàng, lấy tất cả đơn hàng dưới dạng JSON,
 * scập nhật trạng thái đơn hàng và hủy đơn hàng.
 */
class OrdersController extends BaseController
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = $this->model('OrderModel');
    }

    /**
     * Phương thức index gọi phương thức getOrders từ mô hình OrderModel để lấy danh sách đơn hàng.
     * Gọi phương thức view để hiển thị trang danh sách đơn hàng (orders/index) với tiêu đề là "Đơn hàng" và dữ liệu danh sách đơn hàng.
     */
    public function index()
    {
        $orders = $this->orderModel->getOrders();

        $this->view(
            'app',
            [
                'page' => 'orders/index',
                'title' => 'Đơn hàng',
                'orders' => $orders,
            ]
        );
    }

    /**
     * Phương thức all cũng gọi phương thức getOrders từ mô hình OrderModel để lấy danh sách đơn hàng.
     */
    public function all()
    {
        $orders = $this->orderModel->getOrders();

        $result = [
            'status' => 200,
            'data' => $orders
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Phương thức updateOrder nhận tham số $id là mã đơn hàng.
     * Gọi phương thức getOrder từ mô hình OrderModel để lấy thông tin đơn hàng theo mã đơn hàng.
     * Nếu không tìm thấy đơn hàng, trả về mã lỗi 404 và thông báo lỗi.
     * Nếu trạng thái đơn hàng là "đang chờ", gọi phương thức updateStatuShipping để cập nhật trạng thái đơn hàng thành "đang giao".
     * Nếu trạng thái đơn hàng là "đang giao", gọi phương thức updateStatusCompleted để cập nhật trạng thái đơn hàng thành "hoàn thành".
     * Trả về mã trạng thái 200 và thông báo thành công.
     * Nếu có lỗi, trả về mã lỗi 500 và thông báo lỗi.
     */
    public function updateOrder($id)
    {
        try {
            $order = $this->orderModel->getOrder($id);

            if (!$order) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy đơn hàng'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            if ($order['status'] == 'đang chờ') {
                $this->orderModel->updateStatuShipping($id);
            } else if ($order['status'] == 'đang giao') {
                $this->orderModel->updateStatusCompleted($id);
            }

            $result = [
                'status' => 200,
                'message' => "Cập nhật đơn hàng thành công"
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
            // header('Location: /phone-ecommerce-chat/admin/orders');
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => $th->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
     * Phương thức destroy nhận tham số $id là mã đơn hàng.
     * Gọi phương thức getOrder từ mô hình OrderModel để lấy thông tin đơn hàng theo mã đơn hàng.
     * Nếu không tìm thấy đơn hàng, trả về mã lỗi 404 và thông báo lỗi.
     * Gọi phương thức updateStatusCancle từ mô hình OrderModel để cập nhật trạng thái đơn hàng thành "hủy bỏ".
     * Trả về mã trạng thái 204 và thông báo thành công.
     * Nếu có lỗi, trả về mã lỗi 500 và thông báo lỗi.
     */
    public function destroy($id)
    {
        try {
            $order = $this->orderModel->getOrder($id);

            if (!$order) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy đơn hàng'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            $this->orderModel->updateStatusCancle($id);

            $result = [
                'status' => 204,
                'message' => "Cập nhật đơn hàng thành công"
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
            // header('Location: /phone-ecommerce-chat/admin/orders');
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => $th->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    /**
 * Phương thức confirmDelivery cập nhật trạng thái đơn hàng thành "đã giao".
 * Nhận tham số $id là mã đơn hàng.
 * Kiểm tra trạng thái đơn hàng hiện tại, nếu là "đang giao" thì cập nhật thành "đã giao".
 */
    public function confirmDelivery($id)
    {
        try {
            // Lấy thông tin đơn hàng
            $order = $this->orderModel->getOrder($id);

            if (!$order) {
                $result = [
                    'status' => 404,
                    'message' => 'Không tìm thấy đơn hàng'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            // Kiểm tra trạng thái đơn hàng
            if ($order['status'] !== 'đang giao') {
                $result = [
                    'status' => 400,
                    'message' => 'Trạng thái đơn hàng không hợp lệ để xác nhận đã giao.'
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
                return;
            }

            // Cập nhật trạng thái đơn hàng thành "đã giao"
            $this->orderModel->updateOrderStatus($id, 'đã giao');

            $result = [
                'status' => 200,
                'message' => 'Xác nhận đơn hàng đã giao thành công.'
            ];
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => $th->getMessage()
            ];
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

}
