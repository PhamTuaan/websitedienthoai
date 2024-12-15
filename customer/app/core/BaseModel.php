<?php
class BaseModel extends Database
{
    protected $connect;
    protected $table;
    protected $primaryKey;

    public function __construct() {
        $this->connect = $this->HandleConnect();  
        error_log("Database connection status: " . ($this->connect ? "Success" : "Failed"));
        if (!$this->table) {
            $this->table = strtolower(substr(get_class($this), 0, -5));
        }
    }

    public function querySql($sql)
    {
        try {
            error_log("Executing SQL: " . $sql);
            if (!$this->connect) {
                error_log("Database connection failed");
                return false;
            }
            $query = mysqli_query($this->connect, $sql);
            if (!$query) {
                error_log("Query error: " . mysqli_error($this->connect));
            } else {
                error_log("Query success");
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


    public function first() {
        $sql = "SELECT * FROM {$this->table} WHERE deleted_by IS NULL ORDER BY created_at DESC LIMIT 1";
        return $this->querySingle($sql);
    }

    public function findByField($field, $value) {
        $value = mysqli_real_escape_string($this->connect, $value);
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = '{$value}' AND deleted_by IS NULL";
        return $this->querySingle($sql);
    }

    public function count() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE deleted_by IS NULL";
        $result = $this->querySingle($sql);
        return $result ? $result['count'] : 0;
    }

    protected function querySingle($sql) {
        $result = $this->_query($sql);
        return $result ? mysqli_fetch_assoc($result) : null;
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

    public function find($tableName, $id)
    {
        $sql = "SELECT * FROM {$tableName} WHERE id = {$id} AND deleted_by IS NULL";
        $query = $this->_query($sql);
        return mysqli_fetch_assoc($query);
    }


    public function create($tableName, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = array_map(function($value) {
            return "'" . mysqli_real_escape_string($this->connect, $value) . "'";
        }, array_values($data));
        $valueString = implode(', ', $values);
        
        $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$valueString})";
        
        return $this->_query($sql);
    }

    public function update($tableName, $customId, $id, $data)
    {
        $dataSet = [];
        foreach ($data as $key => $value) {
            $escapedValue = mysqli_real_escape_string($this->connect, $value);
            $dataSet[] = "{$key} = '{$escapedValue}'";
        }
        $dataSetString = implode(', ', $dataSet);
        
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date('Y-m-d H:i:s');
        
        $sql = "UPDATE {$tableName} 
                SET {$dataSetString}, updated_at = '{$date}' 
                WHERE {$customId} = {$id}";
                
        return $this->_query($sql);
    }

    public function softDelete($tableName, $id, $userId)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date('Y-m-d H:i:s');
        
        $sql = "UPDATE {$tableName} 
                SET deleted_by = {$userId}, 
                    updated_at = '{$date}', 
                    status = 0
                WHERE id = {$id}";
                
        return $this->_query($sql);
    }


    public function beginTransaction()
    {
        mysqli_begin_transaction($this->connect);
    }

    public function commit()
    {
        mysqli_commit($this->connect);
    }

    public function rollback()
    {
        mysqli_rollback($this->connect);
    }
}