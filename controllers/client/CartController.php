<?php
namespace Controllers\Client;

use DAO\ProductDAO;

class CartController
{
    private ProductDAO $productDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add()
    {
        // 1. Khởi tạo Cart nếu chưa có
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }
        // 2. Nhận productid từ AJAX
        $productid = $_POST["productid"] ?? null;
        // 3. Kiểm tra productid
        if (!$productid) {
            echo json_encode([
                "success" => false,
                "message" => "Sản phẩm không hợp lệ"
            ]);
            exit;
        }
        //4. Lấy sp từ DB
        $product = $this->productDAO->findById($productid);

        // 5. Kiểm tra sản phẩm
        if (!$product) {
            echo json_encode([
                "success" => false,
                "message" => "Không tìm thấy sản phẩm"
            ]);
            exit;
        }

        // 6. Xác định giá bán
        $price = $product->pricediscount > 0
            ? $product->pricediscount
            : $product->price;

        // 7. Kiểm tra sản phẩm đã có trong Cart chưa
        if (isset($_SESSION["cart"][$productid])) {
            // Đã có -> tăng số lượng
            $_SESSION["cart"][$productid]["quantity"]++;
        } else {
            // Chưa có -> thêm sản phẩm
            $_SESSION["cart"][$productid] = [
                "productid"   => $product->id,
                "productname" => $product->productname,
                "image"       => $product->image,
                "price"       => $price,
                "quantity"    => 1
            ];
        }

        // 8. Tính tổng số lượng trong Cart
        $cartCount = 0;

        foreach ($_SESSION["cart"] as $item) {
            $cartCount += $item["quantity"];
        }

        // 9. Trả JSON
        echo json_encode([
            "success" => true,
            "message" => "Đã thêm sản phẩm vào giỏ hàng",
            "cartCount" => $cartCount
        ]);
        exit;
    }

    // Hiển thị trang giỏ hàng
    public function index()
    {
    }

    // Cập nhật số lượng sản phẩm trong giỏ
    public function update()
    {
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove()
    {
    }

    // Lấy số lượng trên Header
    public function count()
    {
    }

    // Xử lý đặt hàng
    public function checkout()
    {
    }
}
