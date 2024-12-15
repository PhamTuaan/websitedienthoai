<?php
class CartModel extends BaseModel
{
    const TableName = 'carts'; // Định nghĩa tên bảng 'carts' trong cơ sở dữ liệu.

    // Phương thức này dùng để tạo một giỏ hàng mới.
    public function createCart($data)
    {
        // Gọi phương thức `create` từ lớp cha (BaseModel) để tạo bản ghi mới trong bảng 'carts'.
        return $this->create(self::TableName, $data);
    }
}
