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
        // 4. Lấy sản phẩm từ Database
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
                "productname" => $product->proname,
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
    public function index()
    {
        //Lấy giỏ hàng từ Session
        $cart = $_SESSION[CART_SESSION_KEY] ?? [];
        //Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item["price"] * $item["quantity"];
        }
        // Tiêu đề trang
        $title = "Giỏ hàng";
        //Bắt đầu nội dung View
        ob_start();
        require __DIR__ . "/../../views/client/cart/index.php";
        $content = ob_get_clean();
        // hiển thị layout chung
        require __DIR__ . "/../../views/client/layouts/master.php";
    }

    public function update()
    {
        // 1. Nhận dữ liệu từ AJAX
        $productid = $_POST["productid"] ?? null;
        $quantity = (int)($_POST["quantity"] ?? 1);
        // 2. Kiểm tra sản phẩm có trong Cart không
        if ($productid && isset($_SESSION[CART_SESSION_KEY][$productid])) {
            if ($quantity > 0) {
                $_SESSION[CART_SESSION_KEY][$productid]["quantity"] = $quantity;
            } else {
                unset($_SESSION[CART_SESSION_KEY][$productid]);
            }
        }
        // 3. Tính lại tổng số lượng trong Cart
        $cartCount = 0;
        if (isset($_SESSION[CART_SESSION_KEY])) {
            foreach ($_SESSION[CART_SESSION_KEY] as $item) {
                $cartCount += $item["quantity"];
            }
        }
        // 4. Trả JSON
        echo json_encode([
            "success"   => true,
            "cartCount" => $cartCount
        ]);
        exit;
    }
    public function remove()
    {
        // 1. Nhận productid từ AJAX
        $productid = $_POST["productid"] ?? null;

        // 2. Kiểm tra sản phẩm có trong Cart không
        if ($productid && isset($_SESSION[CART_SESSION_KEY][$productid])) {
            unset($_SESSION[CART_SESSION_KEY][$productid]);
        }

        // 3. Tính lại tổng số lượng trong Cart
        $cartCount = 0;
        if (isset($_SESSION[CART_SESSION_KEY])) {
            foreach ($_SESSION[CART_SESSION_KEY] as $item) {
                $cartCount += $item["quantity"];
            }
        }

        // 4. Trả JSON
        echo json_encode([
            "success"   => true,
            "message"   => "Đã xóa sản phẩm khỏi giỏ hàng",
            "cartCount" => $cartCount
        ]);
        exit;
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
