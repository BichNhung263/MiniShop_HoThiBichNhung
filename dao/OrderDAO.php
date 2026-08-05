<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/OrderDetail.php";

class OrderDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // Lấy tất cả đơn hàng
    public function getAll(): array
    {
        $list = [];
        try {
            $sql = "SELECT * FROM orders ORDER BY id DESC";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $order = new Order(
                    (int)$row["customer_id"],
                    $row["user_id"] ? (int)$row["user_id"] : null,
                    $row["order_code"],
                    (float)$row["total_amount"],
                    $row["note"],
                    (int)$row["status"]
                );
                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];
                $list[] = $order;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Đếm tổng số đơn hàng
    public function countAll(): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM orders";
            $result = $this->executeQuery($sql);
            if ($row = $result->fetch_assoc()) {
                return (int)$row['total'];
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 0;
    }

    // Lấy 5 đơn hàng mới nhất (JOIN với bảng customers)
    public function getTop5Latest(): array
    {
        $list = [];
        try {
            $sql = "SELECT o.*, c.fullname, c.phone 
                    FROM orders o 
                    LEFT JOIN customers c ON o.customer_id = c.id 
                    ORDER BY o.id DESC LIMIT 5";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $order = new Order(
                    (int)$row["customer_id"],
                    $row["user_id"] ? (int)$row["user_id"] : null,
                    $row["order_code"],
                    (float)$row["total_amount"],
                    $row["note"],
                    (int)$row["status"]
                );
                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];
                $order->customerName = $row["fullname"] ?? "Khách lẻ";
                $order->customerPhone = $row["phone"] ?? "";
                $list[] = $order;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Tìm theo ID
    public function findById(int $id): ?Order
    {
        try {
            $sql = "SELECT * FROM orders WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $order = new Order(
                    (int)$row["customer_id"],
                    $row["user_id"] ? (int)$row["user_id"] : null,
                    $row["order_code"],
                    (float)$row["total_amount"],
                    $row["note"],
                    (int)$row["status"]
                );
                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];
                return $order;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    // Thêm đơn hàng
    public function insert(Order $order): bool
    {
        try {
            $sql = "INSERT INTO orders(customer_id, user_id, order_code, total_amount, note, status) 
                    VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iisdsi",
                $order->customerId,
                $order->userId,
                $order->orderCode,
                $order->totalAmount,
                $order->note,
                $order->status
            );
            $result = $stmt->execute();
            if ($result) {
                $order->id = $this->getLastInsertId();
            }
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Cập nhật đơn hàng
    public function update(Order $order): bool
    {
        try {
            $sql = "UPDATE orders SET customer_id=?, user_id=?, order_code=?, total_amount=?, note=?, status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iisdsii",
                $order->customerId,
                $order->userId,
                $order->orderCode,
                $order->totalAmount,
                $order->note,
                $order->status,
                $order->id
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Xóa đơn hàng (và các chi tiết đơn hàng tương ứng)
    public function delete(int $id): bool
    {
        try {
            $this->beginTransaction();

            // Xóa các chi tiết đơn hàng trước
            $this->deleteDetailsByOrderId($id);

            // Xóa đơn hàng
            $sql = "DELETE FROM orders WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $res = $stmt->execute();

            $this->commit();
            return $res;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    // --- Các phương thức làm việc với chi tiết đơn hàng (order_details) ---

    // Lấy chi tiết đơn hàng theo order_id
    public function getOrderDetails(int $orderId): array
    {
        $list = [];
        try {
            $sql = "SELECT * FROM order_details WHERE order_id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $detail = new OrderDetail(
                    (int)$row["order_id"],
                    (int)$row["product_id"],
                    (int)$row["quantity"],
                    (float)$row["price"],
                    (float)$row["subtotal"]
                );
                $detail->id = $row["id"];
                $detail->createdAt = $row["created_at"];
                $list[] = $detail;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Thêm chi tiết đơn hàng
    public function insertDetail(OrderDetail $detail): bool
    {
        try {
            $sql = "INSERT INTO order_details(order_id, product_id, quantity, price, subtotal) VALUES(?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iiidd",
                $detail->orderId,
                $detail->productId,
                $detail->quantity,
                $detail->price,
                $detail->subtotal
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Xóa tất cả chi tiết đơn hàng thuộc về một order_id
    public function deleteDetailsByOrderId(int $orderId): bool
    {
        try {
            $sql = "DELETE FROM order_details WHERE order_id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $orderId);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>
