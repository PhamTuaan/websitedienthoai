<?php
class Database {
    const HOST = 'localhost';
    const USERNAME = 'root'; 
    const PASSWORD = '';
    const DB_NAME = 'e_ecommerce_app';

    public function HandleConnect() {
        try {
            $connect = mysqli_connect(self::HOST, self::USERNAME, self::PASSWORD, self::DB_NAME);
            if (!$connect) {
                throw new Exception("Lỗi kết nối cơ sở dữ liệu: " . mysqli_connect_error());
            }
            mysqli_set_charset($connect, 'utf8');
            return $connect;
        } catch (Exception $e) {
            error_log($e->getMessage());
            die("Lỗi kết nối cơ sở dữ liệu");
        }
    }

    
}