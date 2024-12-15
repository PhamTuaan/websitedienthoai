<?php
class OrderDetailModel extends BaseModel
{
    const TableName = 'order_details';

    public function createOrderDetail($order_id, $product_id, $quantity)
    {
        $data = [
            'order_id' => $order_id,
            'product_id' => $product_id,  
            'quantity' => $quantity
        ];

        return $this->create(self::TableName, $data);
    }
}
