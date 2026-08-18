<?php
namespace DAO;

use Models\Product;
use Models\ProductImage;

class ProductDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // Lấy tất cả sản phẩm 
    public function getAll($keyword = ""): array
    {
        $list = [];
        try {
            $sql = "SELECT 
                        p.id, 
                        p.category_id, 
                        p.brand_id, 
                        p.proname, 
                        p.slug, 
                        p.price, 
                        p.discount_price, 
                        p.quantity, 
                        p.image, 
                        p.description, 
                        p.status, 
                        p.created_at, 
                        p.updated_at, 
                        c.catename AS cateName, 
                        b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id";

            if (!empty($keyword)) {
                $sql .= " WHERE p.proname LIKE ? OR c.catename LIKE ? OR b.brandname LIKE ?";
            }
            $sql .= " ORDER BY p.proname";
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
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Tìm kiếm sản phẩm theo từ khóa
    public function search(string $keyword): array
    {
        return $this->getAll($keyword);
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
        } catch (\Exception $e) {
            throw $e;
        }
        return 0;
    }

    // Lấy 5 sản phẩm mới nhất
    public function getLatest(): array
    {
        $list = [];
        try {
            $sql = "SELECT 
                        p.id, 
                        p.category_id, 
                        p.brand_id, 
                        p.proname, 
                        p.slug, 
                        p.price, 
                        p.discount_price, 
                        p.quantity, 
                        p.image, 
                        p.description, 
                        p.status, 
                        p.created_at, 
                        p.updated_at, 
                        c.catename AS cateName, 
                        b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id 
                    ORDER BY p.id DESC LIMIT 5";
            $result = $this->executeQuery($sql);
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Alias cho getDiscount
    public function getDiscountProducts(int $limit = 8): array
    {
        return $this->getDiscount($limit);
    }

    // Lấy danh sách sản phẩm mới nhất với limit tùy chọn
    public function getNewProducts(int $limit = 4): array
    {
        $list = [];
        try {
            $sql = "SELECT 
                        p.id, 
                        p.category_id, 
                        p.brand_id, 
                        p.proname, 
                        p.slug, 
                        p.price, 
                        p.discount_price, 
                        p.quantity, 
                        p.image, 
                        p.description, 
                        p.status, 
                        p.created_at, 
                        p.updated_at, 
                        c.catename AS cateName, 
                        b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id 
                    ORDER BY p.id DESC LIMIT ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Lấy sản phẩm theo danh mục ($slug)
    public function getByCategory(string $slug): array
    {
        $list = [];
        try {
            $sql = "SELECT 
                        p.id, p.category_id, p.brand_id, p.proname, p.slug, 
                        p.price, p.discount_price, p.quantity, p.image, 
                        p.description, p.status, p.created_at, p.updated_at, 
                        c.catename AS cateName, b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id 
                    WHERE c.slug = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Lấy sản phẩm theo thương hiệu ($slug)
    public function getByBrand(string $slug): array
    {
        $list = [];
        try {
            $sql = "SELECT 
                        p.id, p.category_id, p.brand_id, p.proname, p.slug, 
                        p.price, p.discount_price, p.quantity, p.image, 
                        p.description, p.status, p.created_at, p.updated_at, 
                        c.catename AS cateName, b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id 
                    WHERE b.slug = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Lấy chi tiết sản phẩm theo slug
    public function getBySlug(string $slug): ?Product
    {
        try {
            $sql = "SELECT 
                        p.id, p.category_id, p.brand_id, p.proname, p.slug, 
                        p.price, p.discount_price, p.quantity, p.image, 
                        p.description, p.status, p.created_at, p.updated_at, 
                        c.catename AS cateName, b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id 
                    WHERE p.slug = ? LIMIT 1";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                return $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return null;
    }

    // Lấy danh sách sản phẩm liên quan (cùng danh mục)
    public function getRelatedProducts(int $categoryId, int $excludeId, int $limit = 4): array
    {
        $list = [];
        try {
            $sql = "SELECT 
                        p.id, p.category_id, p.brand_id, p.proname, p.slug, 
                        p.price, p.discount_price, p.quantity, p.image, 
                        p.description, p.status, p.created_at, p.updated_at, 
                        c.catename AS cateName, b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id 
                    WHERE p.category_id = ? AND p.id != ? AND p.status = 1 
                    LIMIT ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("iii", $categoryId, $excludeId, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }



    // Lấy sản phẩm giảm giá (discount_price > 0)
    public function getDiscount(int $limit = 8): array

    {
        $list = [];
        try {
            $sql = "SELECT 
                        p.id, 
                        p.category_id, 
                        p.brand_id, 
                        p.proname, 
                        p.slug, 
                        p.price, 
                        p.discount_price, 
                        p.quantity, 
                        p.image, 
                        p.description, 
                        p.status, 
                        p.created_at, 
                        p.updated_at, 
                        c.catename AS cateName, 
                        b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id 
                    WHERE p.discount_price > 0 AND p.status = 1
                    ORDER BY p.discount_price DESC 
                    LIMIT ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }


    public function findById(int $id): ?Product
    {
        try {
            $sql = "SELECT 
                        p.id, 
                        p.category_id, 
                        p.brand_id, 
                        p.proname, 
                        p.slug, 
                        p.price, 
                        p.discount_price, 
                        p.quantity, 
                        p.image, 
                        p.description, 
                        p.status, 
                        p.created_at, 
                        p.updated_at, 
                        c.catename AS cateName, 
                        b.brandname AS brandName 
                    FROM products p 
                    INNER JOIN categories c ON p.category_id = c.id 
                    INNER JOIN brands b ON p.brand_id = b.id 
                    WHERE p.id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                return $product;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return null;
    }

    // Thêm sản phẩm
    public function insert(Product $product): bool
    {
        try {
            $sql = "INSERT INTO 
            products(category_id, brand_id, 
            proname, 
            slug, 
            price, 
            discount_price, 
            quantity, 
            image, 
            description, 
            status) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iissddissi",
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
            $result = $stmt->execute();
            if ($result) {
                $product->id = $this->getLastInsertId();
            }
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Cập nhật sản phẩm
    public function update(Product $product): bool
    {
        try {
            $sql = "UPDATE products SET 
            category_id=?,
            brand_id=?,
            proname=?,
            slug=?,
            price=?,
            discount_price=?,
            quantity=?,
            image=?,
            description=?,
            status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iissddissii",
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            throw $e;
        }
    }
    // Lấy danh sách ảnh phụ của sản phẩm
    public function getImagesByProductId($productId): array
    {
        $list = [];
        try {
            $sql = "SELECT id, product_id, 
            image, sort_order, created_at 
            FROM product_images 
            WHERE product_id=? 
            ORDER BY sort_order ASC";
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
        } catch (\Exception $e) {
            throw $e;
        }
        return $list;
    }

    // Thêm ảnh phụ cho sản phẩm
    public function insertImage($productId, $image): bool
    {
        try {
            $sql = "INSERT INTO product_images(product_id, image) VALUES(?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("is", $productId, $image);
            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Xóa ảnh phụ theo ID
    public function deleteImage($id): bool
    {
        try {
            // Lấy thông tin ảnh để xóa file
            $sql = "SELECT image FROM product_images WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                // Xóa file hình ảnh trong thư mục uploads/products
                $filePath = __DIR__ . "/../uploads/products/" . $row["image"];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            // Xóa dữ liệu trong cơ sở dữ liệu
            $sql2 = "DELETE FROM product_images WHERE id=?";
            $stmt2 = $this->prepare($sql2);
            $stmt2->bind_param("i", $id);
            return $stmt2->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function getPage(int $limit, int $offset, string $keyword="")
    {
        $sql = "SELECT
        p.id,
        p.category_id,
        p.brand_id,
        p.proname,
        p.slug,
        p.price,
        p.discount_price,
        p.quantity,
        p.image,
        p.description,
        p.status,
        c.catename AS cateName,
        b.brandname AS brandName
        FROM products p
        INNER JOIN categories c ON p.category_id= c.id
        INNER JOIN brands b ON p.brand_id=b.id
        WHERE p.proname LIKE ?
        ORDER BY p.proname
        LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("sii",$keyword, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()){
            $product = new Product(
                $row["category_id"],
                $row["brand_id"],
                $row["proname"],
                $row["slug"],
                $row["price"],
                $row["discount_price"],
                $row["quantity"],
                $row["image"],
                $row["description"],
                $row["status"]
            );
            $product->id= $row["id"];
            $product->cateName= $row["cateName"];
            $product->brandName = $row["brandName"];
            $products[]= $product;
        }
        return $products;
    }
}
?>
