<?php
require_once __DIR__ . "/../config/database.php";

class BaseDAO extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    // THỰC THI CÂU LỆNH 
    protected function executeQuery(string $sql): mysqli_result|false
    {
        return $this->conn->query($sql);
    }

    // CHUẨN BỊ CÂU LỆNH Prepared Statement
    protected function prepare(string $sql): mysqli_stmt|false
    {
        return $this->conn->prepare($sql);
    }

    // Bắt đầu Transaction
    protected function beginTransaction(): void
    {
        $this->conn->begin_transaction();
    }

    // Xác nhận Transaction
    protected function commit(): void
    {
        $this->conn->commit();
    }

    // Hủy Transaction
    protected function rollback(): void
    {
        $this->conn->rollback();
    }

    // Lấy ID vừa insert
    protected function getLastInsertId(): int
    {
        return $this->conn->insert_id;
    }

    // Đóng kết nối
    public function close(): void
    {
        parent::close();
    }
}
?>
