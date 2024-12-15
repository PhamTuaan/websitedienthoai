<?php
class ContactController extends BaseController
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactModel');
    }

    public function index()
    {
        $this->view(
            'app',
            [
                'page' => 'contact/index',
                'title' => 'Contact',
            ]
        );
    }

    public function store()
    {
        try {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'], 
                'message' => $_POST['message']
            ];

            $this->contactModel->createContact($data);

            $result = [
                'status' => 200,
                'message' => 'Gửi tin nhắn thành công!'
            ];

        } catch (\Throwable $th) {
            $result = [
                'status' => 500,
                'message' => $th->getMessage()
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}