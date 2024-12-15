<?php

class ProfileController extends BaseController
{

    private $userModel;
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
    }

    /**
     * Đầu tiên, phương thức gọi getUser từ mô hình UserModel để lấy thông tin người dùng hiện tại. Mã người dùng được lấy từ biến session $_SESSION['auth_admin']['user_id'].
     * Sau đó, phương thức gọi view để hiển thị trang thông tin cá nhân (profile) với tiêu đề là "Thông tin cá nhân" và dữ liệu người dùng.
     */
    public function index()
    {
        $user = $this->userModel->getUser($_SESSION['auth_admin']['user_id']);

        $this->view('app', [
            'page' => 'profile',
            'title' => 'Thông tin cá nhân',
            'user' => $user
        ]);
    }
}
