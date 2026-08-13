<?php
namespace DAO;

use Models\Category;

class CategoryDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // Lấy tất cả danh mục
    public function getAll($keyword = "")
    {
        $list = [];
        try {
            $sql = "SELECT id, catename, slug, image, description, status, created_at, updated_at FROM categories";

            if (!empty($keyword)) {
                $sql .= " WHERE catename LIKE ? OR slug LIKE ?";
            }

            $sql .= " ORDER BY catename";

            if (!empty($keyword)) {
                $stmt = $this->prepare($sql);
                $like = "%" . $keyword . "%";
                $stmt->bind_param("ss", $like, $like);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $this->executeQuery($sql);
            }

            while ($row = $result->fetch_assoc()) {
                $category = new Category(
                    $row["catename"],
                    $row["slug"],
                    $row["description"],
                    $row["image"],
                    $row["status"]
                );
                $category->id = $row["id"];
                $category->createdAt = $row["created_at"];
                $category->updatedAt = $row["updated_at"];
                $list[] = $category;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Đếm tổng số danh mục
    public function countAll(): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM categories";
            $result = $this->executeQuery($sql);
            if ($row = $result->fetch_assoc()) {
                return (int)$row['total'];
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 0;
    }

    // Tìm theo ID
    public function findById(int $id): ?Category
    {
        try {
            $sql = "SELECT id, catename, slug, image, description, status, created_at, updated_at FROM categories WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $category = new Category(
                    $row["catename"],
                    $row["slug"],
                    $row["description"],
                    $row["image"],
                    $row["status"]
                );
                $category->id = $row["id"];
                $category->createdAt = $row["created_at"];
                $category->updatedAt = $row["updated_at"];
                return $category;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    // Thêm danh mục
    public function insert(Category $category): bool
    {
        try {
            $sql = "INSERT INTO categories(catename, slug, image, description, status) VALUES(?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "ssssi",
                $category->catename,
                $category->slug,
                $category->image,
                $category->description,
                $category->status
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Cập nhật danh mục
    public function update(Category $category): bool
    {
        try {
            $sql = "UPDATE categories SET catename=?, slug=?, image=?, description=?, status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "ssssii",
                $category->catename,
                $category->slug,
                $category->image,
                $category->description,
                $category->status,
                $category->id
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Xóa danh mục
    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM categories WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function getPage(int $limit, int $offset, string $keyword="")
    {
        $sql = "SELECT
            c.id, 
            c.catename, 
            c.slug, 
            c.image,
            c.description, 
            c.status, 
            c.created_at, 
            c.updated_at
            FROM categories c
            WHERE c.catename LIKE ?
            ORDER BY c.catename
            LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("sii",$keyword, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = [];
        while ($row = $result->fetch_assoc()){
            $category = new Category(
                $row["catename"],
                $row["slug"],
                $row["description"],
                $row["image"],
                $row["status"]
            );
            $category->id = $row["id"];
            $category->createdAt = $row["created_at"];
            $category->updatedAt = $row["updated_at"];
            $categories[] = $category;
        }
        return $categories;
    }
}

?>
