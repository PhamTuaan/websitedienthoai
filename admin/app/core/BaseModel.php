<?php
class BaseModel extends Database
{
    protected $connect;
    protected $table;

    public function __construct() {
        $this->connect = $this->HandleConnect();
        if (!$this->connect) {
            throw new Exception("Failed to connect to database");
        }
    }

    protected function getCurrentAdminId() {
        return isset($_SESSION['auth_admin']['user_id']) ? $_SESSION['auth_admin']['user_id'] : null;
    }

    public function querySql($sql)
    {
        try {
            $query = mysqli_query($this->connect, $sql);
            if (!$query) {
                error_log("Query error: " . mysqli_error($this->connect));
            }
            return $query;
        } catch (Exception $e) {
            error_log("Query error: " . $e->getMessage());
            return false;
        }
    }

    protected function _query($sql)
    {
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            throw new Exception(mysqli_error($this->connect));
        }
        return $result;
    }

    public function getAll($tableName, $select = ['*'], $orderBy = [])
    {
        $columns = implode(', ', $select);
        $orderByString = implode(' ', $orderBy);
        
        $sql = "SELECT {$columns} FROM {$tableName} WHERE deleted_by IS NULL";
        
        if ($orderByString) {
            $sql .= " ORDER BY {$orderByString}";
        }

        $query = $this->_query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }

    public function create($tableName, $data)
    {
        try {
            $adminId = $this->getCurrentAdminId();
            if ($adminId) {
                $data['created_by'] = $adminId;
            }

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            $columns = implode(', ', array_keys($data));
            $values = array_map(function($value) {
                return "'" . mysqli_real_escape_string($this->connect, $value) . "'";
            }, array_values($data));
            $valueString = implode(', ', $values);
            
            $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$valueString})";
            
            return $this->_query($sql);
        } catch (Exception $e) {
            error_log("Create Error: " . $e->getMessage());
            return false;
        }
    }

    public function update($tableName, $customId, $id, $data)
    {
        try {
            $adminId = $this->getCurrentAdminId();
            if ($adminId) {
                $data['updated_by'] = $adminId;
            }

            $dataSet = [];
            foreach ($data as $key => $value) {
                if ($value === null || $value === '') {
                    $dataSet[] = "{$key} = NULL";
                } else {
                    $escapedValue = mysqli_real_escape_string($this->connect, $value);
                    $dataSet[] = "{$key} = '{$escapedValue}'";
                }
            }
            
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $updateTime = date('Y-m-d H:i:s');
            $dataSet[] = "updated_at = '{$updateTime}'";
            
            $dataSetString = implode(', ', $dataSet);
            
            $sql = "UPDATE {$tableName} 
                    SET {$dataSetString}
                    WHERE {$customId} = {$id} AND deleted_by IS NULL";
                    
            return $this->_query($sql);
        } catch (Exception $e) {
            error_log("Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($tableName, $id)
    {
        try {
            $adminId = $this->getCurrentAdminId();
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $date = date('Y-m-d H:i:s');
            
            $sql = "UPDATE {$tableName} 
                    SET deleted_by = {$adminId}, 
                        updated_at = '{$date}', 
                        status = 0 
                    WHERE id = {$id}";
                    
            return $this->_query($sql);
        } catch (Exception $e) {
            error_log("Delete Error: " . $e->getMessage());
            return false;
        }
    }

    public function beginTransaction() {
        mysqli_begin_transaction($this->connect);
    }

    public function commit() {
        mysqli_commit($this->connect);
    }

    public function rollback() {
        mysqli_rollback($this->connect);
    }

    public function __destruct() {
        if ($this->connect) {
            mysqli_close($this->connect);
        }
    }
}