<?php

namespace DAO;

use Models\User;

class UserDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // Lấy tất cả người dùng (có hỗ trợ tìm kiếm)
    public function getAll($keyword = ""): array
    {
        $list = [];
        try {
            $sql = "SELECT id, fullname, username, password, email, phone, address, role, status, created_at, updated_at FROM users";
            if (!empty($keyword)) {
                $sql .= " WHERE fullname LIKE ? OR username LIKE ? OR email LIKE ?";
            }
            $sql .= " ORDER BY id DESC";

            if (!empty($keyword)) {
                $stmt = $this->prepare($sql);
                $like = "%" . $keyword . "%";
                $stmt->bind_param("sss", $like, $like, $like);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $this->executeQuery($sql);
            }

            while ($row = $result->fetch_assoc()) {
                $user = new User(
                    $row["fullname"],
                    $row["username"],
                    $row["password"],
                    $row["email"],
                    $row["phone"],
                    $row["address"],
                    $row["role"],
                    $row["status"]
                );
                $user->id = $row["id"];
                $user->createdAt = $row["created_at"];
                $user->updatedAt = $row["updated_at"];
                $list[] = $user;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Đếm tổng số người dùng
    public function countAll(): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users";
            $result = $this->executeQuery($sql);
            if ($row = $result->fetch_assoc()) {
                return (int)$row['total'];
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return 0;
    }

    // Tìm theo ID
    public function findById(int $id): ?User
    {
        try {
            $sql = "SELECT id, fullname, username, password, email, phone, address, role, status, created_at, updated_at FROM users WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $user = new User(
                    $row["fullname"],
                    $row["username"],
                    $row["password"],
                    $row["email"],
                    $row["phone"],
                    $row["address"],
                    (int)$row["role"],
                    (int)$row["status"]
                );
                $user->id = $row["id"];
                $user->createdAt = $row["created_at"];
                $user->updatedAt = $row["updated_at"];
                return $user;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return null;
    }

    // Thêm người dùng
    public function insert(User $user): bool
    {
        try {
            $sql = "INSERT INTO users(fullname, username, password, email, phone, address, role, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "ssssssii",
                $user->fullname,
                $user->username,
                $user->password,
                $user->email,
                $user->phone,
                $user->address,
                $user->role,
                $user->status
            );
            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Cập nhật người dùng
    public function update(User $user): bool
    {
        try {
            $sql = "UPDATE users SET fullname=?, username=?, password=?, email=?, phone=?, address=?, role=?, status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "ssssssiii",
                $user->fullname,
                $user->username,
                $user->password,
                $user->email,
                $user->phone,
                $user->address,
                $user->role,
                $user->status,
                $user->id
            );
            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Xóa người dùng
    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM users WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getPage(int $limit, int $offset, string $keyword = "")
    {
        $sql = "SELECT
            id,
            fullname,
            username,
            password,
            email,
            phone,
            address,
            role,
            status,
            created_at,
            updated_at
        FROM users
        WHERE fullname LIKE ? OR username LIKE ? OR email LIKE ?
        ORDER BY id DESC
        LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("sssii", $keyword, $keyword, $keyword, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = new User(
                $row["fullname"],
                $row["username"],
                $row["password"],
                $row["email"],
                $row["phone"],
                $row["address"],
                $row["role"],
                $row["status"]
            );
            $user->id = $row["id"];
            $user->createdAt = $row["created_at"];
            $user->updatedAt = $row["updated_at"];
            $users[] = $user;
        }
        return $users;
    }


    public function findByUsername(string $username): ?User
    {
        $sql = "SELECT * FROM users WHERE username =?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row) {
            return null;
        }
        return new User(
            $row['fullname'],
            $row['username'],
            $row['password'],
            $row['email'],
            $row['phone'],
            $row['address'],
            $row['role'],
            $row['status']
        );
    }
}
