<?php
class PromotionModel extends BaseModel
{
    const TableName = 'promotions';

    public function createPromotion($data){
        return $this->create(self::TableName, $data);
    }

    public function getPromotionByCode($code){
        $sql = "SELECT * FROM promotions 
            WHERE promotion_code = '{$code}'
            AND promotion_used = 0 
            AND status = 1
            AND deleted_by IS NULL";
        $result = $this->querySql($sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    public function getPromotionById($id) {
        $sql = "SELECT * FROM promotions 
            WHERE promotion_id = '{$id}'
            AND status = 1 
            AND deleted_by IS NULL";
        $result = $this->querySql($sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    public function updatePromotion($id){
        $sql = "UPDATE " . self::TableName . " SET 	promotion_used = 1 WHERE promotion_id = '{$id}'";
        $result = $this->querySql($sql);
        return $result;
    }
}
