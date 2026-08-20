<?php
namespace DAO;
use Models\Order;
use Models\OrderDetail;
class OrderDAO extends BaseDAO
{
    public function __construct(?\mysqli $conn = null)
    {
        parent::__construct($conn);
    }
    // Lấy tất cả đơn hàng
    public function getAll($keyword = "", $status = null): array
    {
        $list = [];
        try {
            $sql = "SELECT o.id, o.customer_id,
                        o.user_id, o.order_code,
                        o.total_amount, o.note,
                        o.status, o.created_at, o.updated_at,
                        c.fullname AS customerName, u.fullname AS userName
                    FROM orders o
                    LEFT JOIN customers c ON o.customer_id = c.id
                    LEFT JOIN users u ON o.user_id = u.id";

            $conds = [];
            $params = [];
            $types = "";

            if (!empty($keyword)) {
                $conds[] = "(o.order_code LIKE ? OR c.fullname LIKE ?)";
                $like = "%" . $keyword . "%";
                $types .= "ss";
                $params[] = &$like;
                $params[] = &$like;
            }

            if ($status !== null && $status !== "") {
                $conds[] = "o.status = ?";
                $st = (int)$status;
                $types .= "i";
                $params[] = &$st;
            }

            if (count($conds) > 0) {
                $sql .= " WHERE " . implode(" AND ", $conds);
            }

            $sql .= " ORDER BY o.id DESC";

            if (count($params) > 0) {
                $stmt = $this->prepare($sql);
                $bindNames = array_merge([$types], $params);
                call_user_func_array([$stmt, 'bind_param'], $bindNames);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $this->executeQuery($sql);
            }
            while ($row = $result->fetch_assoc()) {
                $order = new Order(
                    $row["customer_id"],
                    $row["user_id"] ? $row["user_id"] : null,
                    $row["order_code"],
                    $row["total_amount"],
                    $row["note"],
                    $row["status"]
                );
                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];
                $order->customerName = $row["customerName"] ?? null;
                $order->userName = $row["userName"] ?? null;
                $list[] = $order;
            }
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            throw $e;
        }
        return 0;
    }
    // Lấy 5 đơn hàng mới nhất
    public function getLatest(): array
    {
        $list = [];
        try {
            $sql = "SELECT o.id, o.customer_id, 
            o.user_id, o.order_code, 
            o.total_amount, o.note, 
            o.status, o.created_at, 
            o.updated_at, c.fullname AS customerName, c.phone 
                    FROM orders o 
                    LEFT JOIN customers c ON o.customer_id = c.id 
                    ORDER BY o.id DESC LIMIT 5";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $order = new Order(
                    $row["customer_id"],
                    $row["user_id"],
                    $row["order_code"],
                    $row["total_amount"],
                    $row["note"],
                    $row["status"]
                );
                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];
                $order->customerName = $row["customerName"] ?? null;

                $list[] = $order;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }
    // Tìm theo ID
    public function findById(int $id): ?Order
    {
        try {
            $sql = "SELECT o.id, o.customer_id, 
            o.user_id, o.order_code, 
            o.total_amount, o.note, o.status, 
            o.created_at, o.updated_at,
            c.fullname AS customerName, u.fullname AS userName
                    FROM orders o
                    LEFT JOIN customers c ON o.customer_id = c.id
                    LEFT JOIN users u ON o.user_id = u.id
                    WHERE o.id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $order = new Order(
                    $row["customer_id"],
                    $row["user_id"],
                    $row["order_code"],
                    $row["total_amount"],
                    $row["note"],
                    $row["status"]
                );
                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];
                $order->customerName = $row["customerName"] ?? null;
                $order->userName = $row["userName"] ?? null;
                return $order;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return null;
    }
    // Thêm đơn hàng
    public function insert(Order $order): bool
    {
        try {
            $sql = "INSERT INTO orders
            (customer_id, user_id, order_code, total_amount, note, status) 
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
        } catch (\Exception $e) {
            throw $e;
        }
    }
    // Cập nhật đơn hàng
    public function update(Order $order): bool
    {
        try {
            $sql = "UPDATE orders SET 
            customer_id=?, user_id=?, 
            order_code=?, total_amount=?, 
            note=?, status=? WHERE id=?";
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
        } catch (\Exception $e) {
            throw $e;
        }
    }
    // Xóa đơn hàng
    public function delete(int $id): bool
    {
        try {
            $this->beginTransaction();
            $this->deleteDetailsByOrderId($id);
            $sql = "DELETE FROM orders WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $res = $stmt->execute();
            $this->commit();
            return $res;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
    // Lấy chi tiết đơn hàng theo order_id
    public function getOrderDetails(int $orderId): array
    {
        $list = [];
        try {
            $sql = "SELECT 
            od.id, od.order_id, od.product_id, od.quantity, od.price, od.subtotal, od.created_at,
                        p.proname AS productName
                    FROM order_details od
                    LEFT JOIN products p ON od.product_id = p.id
                    WHERE od.order_id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $detail = new OrderDetail(
                    $row["order_id"],
                    $row["product_id"],
                    $row["quantity"],
                    $row["price"],
                    $row["subtotal"]
                );
                $detail->id = $row["id"];
                $detail->createdAt = $row["created_at"];
                $detail->productName = $row["productName"] ?? null;
                $list[] = $detail;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }
    // Thêm chi tiết đơn hàng
    public function insertDetail(OrderDetail $detail): bool
    {
        try {
            $sql = "INSERT INTO order_details
            (order_id, product_id, quantity, price, subtotal)
             VALUES(?, ?, ?, ?, ?)";
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            throw $e;
        }
    }
    // Cập nhật trạng thái đơn hàng (riêng)
    public function updateStatus(int $orderId, int $status): bool
    {
        try {
            $sql = "UPDATE orders SET status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("ii", $status, $orderId);
            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function getPage(int $limit, int $offset, string $keyword = "")
    {
        $sql = "SELECT
            o.id,
            o.customer_id, 
            o.user_id, 
            o.order_code, 
            o.total_amount, 
            o.note, 
            o.status, 
            o.created_at, 
            o.updated_at, 
            c.fullname AS customerName, 
            u.fullname AS userName
                    FROM orders o
                    LEFT JOIN customers c ON o.customer_id = c.id
                    LEFT JOIN users u ON o.user_id = u.id
                    WHERE (o.order_code LIKE ? OR c.phone LIKE ? OR c.fullname LIKE ?)
                    ORDER BY o.id DESC
                    LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $like = "%$keyword%";
        $stmt->bind_param("sssii", $like, $like, $like, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $order = new Order(
                $row["customer_id"],
                $row["user_id"],
                $row["order_code"],
                $row["total_amount"],
                $row["note"],
                $row["status"]
            );
            $order->id = $row["id"];
            $order->createdAt = $row["created_at"];
            $order->updatedAt = $row["updated_at"];
            $order->customerName = $row["customerName"];
            $order->userName = $row["userName"];
            $orders[] = $order;
        }
        return $orders;
    }
}
