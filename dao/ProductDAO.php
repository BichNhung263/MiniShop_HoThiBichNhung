<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../models/ProductImage.php";

class ProductDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // Lấy tất cả sản phẩm
    public function getAll(): array
    {
        $list = [];
        try {
            $sql = "SELECT * FROM products ORDER BY id DESC";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"] ? (int)$row["category_id"] : null,
                    $row["brand_id"] ? (int)$row["brand_id"] : null,
                    $row["proname"],
                    $row["slug"],
                    (float)$row["price"],
                    (float)$row["discount_price"],
                    (int)$row["quantity"],
                    $row["image"],
                    $row["description"],
                    (int)$row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $list[] = $product;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Đếm tổng số sản phẩm
    public function countAll(): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM products";
            $result = $this->executeQuery($sql);
            if ($row = $result->fetch_assoc()) {
                return (int)$row['total'];
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 0;
    }

    // Lấy 5 sản phẩm mới nhất
    public function getTop5Latest(): array
    {
        $list = [];
        try {
            $sql = "SELECT p.*, c.catename, b.brandname 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN brands b ON p.brand_id = b.id 
                    ORDER BY p.id DESC LIMIT 5";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"] ? (int)$row["category_id"] : null,
                    $row["brand_id"] ? (int)$row["brand_id"] : null,
                    $row["proname"],
                    $row["slug"],
                    (float)$row["price"],
                    (float)$row["discount_price"],
                    (int)$row["quantity"],
                    $row["image"],
                    $row["description"],
                    (int)$row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                // Lưu tên danh mục & thương hiệu dưới dạng thuộc tính động
                $product->catename = $row["catename"] ?? "Chưa phân loại";
                $product->brandname = $row["brandname"] ?? "Chưa có thương hiệu";
                $list[] = $product;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Tìm theo ID
    public function findById(int $id): ?Product
    {
        try {
            $sql = "SELECT * FROM products WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"] ? (int)$row["category_id"] : null,
                    $row["brand_id"] ? (int)$row["brand_id"] : null,
                    $row["proname"],
                    $row["slug"],
                    (float)$row["price"],
                    (float)$row["discount_price"],
                    (int)$row["quantity"],
                    $row["image"],
                    $row["description"],
                    (int)$row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                return $product;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    // Thêm sản phẩm
    public function insert(Product $product): bool
    {
        try {
            $sql = "INSERT INTO products(category_id, brand_id, proname, slug, price, discount_price, quantity, image, description, status) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iisdddissi",
                $product->categoryId,
                $product->brandId,
                $product->proname,
                $product->slug,
                $product->price,
                $product->discountPrice,
                $product->quantity,
                $product->image,
                $product->description,
                $product->status
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Cập nhật sản phẩm
    public function update(Product $product): bool
    {
        try {
            $sql = "UPDATE products SET category_id=?, brand_id=?, proname=?, slug=?, price=?, discount_price=?, quantity=?, image=?, description=?, status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iisdddissii",
                $product->categoryId,
                $product->brandId,
                $product->proname,
                $product->slug,
                $product->price,
                $product->discountPrice,
                $product->quantity,
                $product->image,
                $product->description,
                $product->status,
                $product->id
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Xóa sản phẩm
    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM products WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // --- Các phương thức xử lý hình ảnh sản phẩm (product_images) ---

    // Lấy danh sách ảnh phụ của sản phẩm
    public function getImagesByProductId(int $productId): array
    {
        $list = [];
        try {
            $sql = "SELECT * FROM product_images WHERE product_id=? ORDER BY sort_order ASC";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $img = new ProductImage(
                    (int)$row["product_id"],
                    $row["image"],
                    (int)$row["sort_order"]
                );
                $img->id = $row["id"];
                $img->createdAt = $row["created_at"];
                $list[] = $img;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Thêm ảnh phụ cho sản phẩm
    public function insertImage(ProductImage $productImage): bool
    {
        try {
            $sql = "INSERT INTO product_images(product_id, image, sort_order) VALUES(?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "isi",
                $productImage->productId,
                $productImage->image,
                $productImage->sortOrder
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Xóa ảnh phụ theo ID
    public function deleteImage(int $imageId): bool
    {
        try {
            $sql = "DELETE FROM product_images WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $imageId);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>
