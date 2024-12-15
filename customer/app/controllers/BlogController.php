<?php
class BlogController extends BaseController
{
    public function index()
    {
        $this->view(
            'app',
            [
                'page' => 'blog/index',
                'title' => 'blog',
            ]
        );
    }

    public function detail($id)
    {
        $this->view(
            'app',
            [
                'page' => 'blog/detail',
                'title' => 'blog',
            ]
        );
    }
}
