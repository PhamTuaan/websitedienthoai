<?php
class HomeController extends BaseController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = $this->model('ProductModel');
    }

    public function index()
    {
        $this->view(
            'app',
            [
                'page' => 'index',
                'title' => 'Trang chủ',
            ]
        );
    }

    public function getNewProduct()
    {
        $newProduct = $this->productModel->getTop10NewProduct();

        if (!$newProduct) {
            $result = [
                'status' => 204,
                'message' => "Lỗi fetch sản phẩm!"
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
            return;
        }

        $result = [ 
            'status' => 200,
            'message' => "success",
            'data' => $newProduct,
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }


    public function hotTrends()
    {
        $newProduct = $this->productModel->getHotTrend();

        if (!$newProduct) {
            $result = [
                'status' => 204,
                'message' => "Lỗi fetch hot trends sản phẩm!"
            ];

            header('Content-Type: application/json');
            echo json_encode($result); 
            return;
        }

        $result = [
            'status' => 200,
            'message' => "success",
            'data' => $newProduct,
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function bestSell()
    {
        $products = $this->productModel->getTop10Seller();

        if (!$products) {
            $result = [
                'status' => 204,
                'message' => "Lỗi fetch topSeller sản phẩm!"
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
            return;
        }

        $result = [
            'status' => 200,
            'message' => "success",
            'data' =>  $products,
        ];

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
