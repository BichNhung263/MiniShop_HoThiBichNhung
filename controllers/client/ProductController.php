<?php
namespace Controllers\Client;

use DAO\ProductDAO;

class ProductController
{
    private ProductDAO $productDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();
    }

    // Sản phẩm theo danh mục
    public function category()
    {
        // Đọc slug danh mục từ URL
        // category/laptop → slug = laptop
        $slug = $_GET['slug'] ?? '';
        // Gọi ProductDAO để lấy sản phẩm theo slug
        $products = $this->productDAO->getByCategory($slug);

        // Chuyển danh sách sản phẩm sang View
        ob_start();
        require __DIR__ . '/../../views/client/products/index.php';
        $content = ob_get_clean();
        require __DIR__ . "/../../views/client/layouts/master.php";
    }

    // Sản phẩm theo thương hiệu
    public function brand()
    {
        // Đọc slug thương hiệu từ URL
        // /brand/asus → slug = asus
        $slug = $_GET['slug'] ?? '';
        // Gọi ProductDAO để lấy sản phẩm theo slug
        $products = $this->productDAO->getByBrand($slug);

        // Chuyển danh sách sản phẩm sang View
        ob_start();
        require __DIR__ . '/../../views/client/products/index.php';
        $content = ob_get_clean();
        require __DIR__ . "/../../views/client/layouts/master.php";
    }

    // Chi tiết sản phẩm
    public function detail()
    {
        // Đọc slug sản phẩm từ URL
        // /product/chuot-logitech → slug = chuot-logitech
        $slug = $_GET['slug'] ?? '';
        // Gọi ProductDAO để lấy chi tiết sản phẩm theo slug
        $product = $this->productDAO->getBySlug($slug);

        // Sản phẩm liên quan (cùng danh mục)
        $relatedProducts = [];
        if ($product && !empty($product->categoryId)) {
            $relatedProducts = $this->productDAO->getRelatedProducts($product->categoryId, $product->id, 4);
        }

        $title = $product ? $product->proname : "Sản phẩm không tồn tại";

        // Chuyển sang View
        ob_start();
        require __DIR__ . '/../../views/client/products/detail.php';
        $content = ob_get_clean();
        require __DIR__ . "/../../views/client/layouts/master.php";
    }

    // Tìm kiếm sản phẩm
    public function search()
    {
        // Đọc từ khóa tìm kiếm từ Request
        $keyword = trim($_GET['keyword'] ?? '');
        $products = [];

        if (!empty($keyword)) {
            $products = $this->productDAO->search($keyword);
        }

        $title = !empty($keyword) ? "Kết quả tìm kiếm cho: " . $keyword : "Tìm kiếm sản phẩm";

        // Chuyển kết quả sang View
        ob_start();
        require __DIR__ . '/../../views/client/products/search.php';
        $content = ob_get_clean();
        require __DIR__ . "/../../views/client/layouts/master.php";
    }
}