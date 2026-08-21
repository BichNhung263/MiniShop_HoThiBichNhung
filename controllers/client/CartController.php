<?php
namespace Controllers\Client;
use DAO\ProductDAO;
use DAO\CustomerDAO;
use DAO\OrderDAO;
use Models\Customer;
use Models\Order;
use Models\OrderDetail;

class CartController
{
    private ProductDAO $productDAO;
    private CustomerDAO $customerDAO;
    private OrderDAO $orderDAO;
    public function __construct()
    {
        $this->productDAO = new ProductDAO();
        $this->customerDAO = new CustomerDAO();
        $this->orderDAO = new OrderDAO($this->customerDAO->getConnection());
    }
    public function add()
    {
        //Khởi tạo Cart nếu chưa có
        if (!isset($_SESSION[CART_SESSION_KEY])) {
            $_SESSION[CART_SESSION_KEY] = [];
        }
        //Nhận productid từ AJAX
        $productid = $_POST["productid"] ?? null;
        //Kiểm tra productid
        if (!$productid) {
            echo json_encode([
                "success" => false,
                "message" => "Sản phẩm không hợp lệ"
            ]);
            exit;
        }
        //Lấy sản phẩm từ Database
        $product = $this->productDAO->findById($productid);
        //Kiểm tra sản phẩm
        if (!$product) {
            echo json_encode([
                "success" => false,
                "message" => "Không tìm thấy sản phẩm"
            ]);
            exit;
        }
        //Xác định giá bán
        $price = $product->pricediscount > 0
            ? $product->pricediscount
            : $product->price;
        //Kiểm tra sản phẩm đã có trong Cart chưa
        if (isset($_SESSION[CART_SESSION_KEY][$productid])) {
            // Đã có 
            $_SESSION[CART_SESSION_KEY][$productid]["quantity"]++;
        } else {
            // Chưa có
            $_SESSION[CART_SESSION_KEY][$productid] = [
                "productid"   => $product->id,
                "productname" => $product->proname,
                "image"       => $product->image,
                "price"       => $price,
                "quantity"    => 1
            ];
        }
        //Tính tổng số lượng trong Cart
        $cartCount = 0;
        foreach ($_SESSION[CART_SESSION_KEY] as $item) {
            $cartCount += $item["quantity"];
        }
        //Trả JSON
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
        //Nhận dữ liệu từ AJAX
        $productid = $_POST["productid"] ?? null;
        $quantity = (int)($_POST["quantity"] ?? 1);
        //Kiểm tra sản phẩm có trong Cart không
        if ($productid && isset($_SESSION[CART_SESSION_KEY][$productid])) {
            if ($quantity > 0) {
                $_SESSION[CART_SESSION_KEY][$productid]["quantity"] = $quantity;
            } else {
                unset($_SESSION[CART_SESSION_KEY][$productid]);
            }
        }
        //Tính lại tổng số lượng trong Cart
        $cartCount = 0;
        if (isset($_SESSION[CART_SESSION_KEY])) {
            foreach ($_SESSION[CART_SESSION_KEY] as $item) {
                $cartCount += $item["quantity"];
            }
        }
        //Trả JSON
        echo json_encode([
            "success"   => true,
            "cartCount" => $cartCount
        ]);
        exit;
    }
    public function remove()
    {
        //Nhận productid từ AJAX
        $productid = $_POST["productid"] ?? null;
        //Kiểm tra sản phẩm có trong Cart không
        if ($productid && isset($_SESSION[CART_SESSION_KEY][$productid])) {
            unset($_SESSION[CART_SESSION_KEY][$productid]);
        }
        //Tính lại tổng số lượng trong Cart
        $cartCount = 0;
        if (isset($_SESSION[CART_SESSION_KEY])) {
            foreach ($_SESSION[CART_SESSION_KEY] as $item) {
                $cartCount += $item["quantity"];
            }
        }
        //Trả JSON
        echo json_encode([
            "success"   => true,
            "message"   => "Đã xóa sản phẩm khỏi giỏ hàng",
            "cartCount" => $cartCount
        ]);
        exit;
    }
    //Lấy số lượng trên Header
    public function count()
    {
    }
    // Hiển thị form đặt hàng
    public function checkout()
    {
        $cart = $_SESSION[CART_SESSION_KEY] ?? [];
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $title = "Đặt hàng";
            ob_start();
            require __DIR__ . "/../../views/client/cart/checkout.php";
            $content = ob_get_clean();
            require __DIR__ . "/../../views/client/layouts/master.php";
            return;
        }
        //Nhận dữ liệu từ form
        $fullname = trim($_POST["fullname"] ?? "");
        $email = trim($_POST["email"] ?? ($_SESSION["client_user"]["email"] ?? ""));
        $phone = trim($_POST["phone"] ?? "");
        $address = trim($_POST["address"] ?? "");
        $note = trim($_POST["note"] ?? "");
        $paymentMethod = trim($_POST["payment_method"] ?? "cod");
        //Kiểm tra dữ liệu và giỏ hàng
        if (empty($fullname) || empty($phone) || empty($address) || empty($cart)) {
            $_SESSION["order_error"] = "Vui lòng nhập đầy đủ thông tin bắt buộc và kiểm tra giỏ hàng!";
            header("Location: " . BASE_URL . "/cart/checkout");
            exit;
        }
        //Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item["price"] * $item["quantity"];
        }
        try {
            // Bắt đầu Transaction
            $this->customerDAO->beginTransaction();
            //Tìm Customer theo số điện thoại
            $customer = $this->customerDAO->findByPhone($phone);
            if (!$customer) {
                //Chưa tồn tại -> tạo Customer mới
                $customer = new Customer($fullname, $email, $phone, $address, $note);
                $this->customerDAO->insert($customer);
            }
            //Lấy ID người dùng nếu đã đăng nhập (để liên kết đơn hàng với tài khoản khách hàng)
            $userId = isset($_SESSION["client_user"]) ? (int)$_SESSION["client_user"]["id"] : null;
            $orderCode = "ORD" . date("YmdHis");
            $orderNote = $note . ($paymentMethod === "vnpay" ? " [VNPAY]" : " [COD]");
            $order = new Order($customer->id, $userId, $orderCode, $total, $orderNote, 0);
            $this->orderDAO->insert($order);
            //Lưu các OrderDetail
            foreach ($cart as $item) {
                $subtotal = $item["price"] * $item["quantity"];
                $detail = new OrderDetail(
                    $order->id,
                    $item["productid"],
                    $item["quantity"],
                    $item["price"],
                    $subtotal
                );
                $this->orderDAO->insertDetail($detail);
            }
            //Thành công 
            $this->customerDAO->commit();
            if ($paymentMethod === "vnpay") {
                $vnpayUrl = \Services\VNPayService::createPaymentUrl($orderCode, $total);
                header("Location: " . $vnpayUrl);
                exit;
            }
            unset($_SESSION[CART_SESSION_KEY]);
            $title = "Đặt hàng thành công";
            $orderSuccess = true;
            ob_start();
            require __DIR__ . "/../../views/client/cart/checkout.php";
            $content = ob_get_clean();
            require __DIR__ . "/../../views/client/layouts/master.php";
            return;
        } catch (\Exception $e) {
            $this->customerDAO->rollback();
            $_SESSION["order_error"] = "Đặt hàng thất bại. Vui lòng thử lại!";
            header("Location: " . BASE_URL . "/cart/checkout");
            exit;
        }
    }
    // Kết quả trả về từ VNPAY sau khi thanh toán
    public function vnpay_return()
    {
        $isValid   = \Services\VNPayService::validateReturn($_GET);
        $orderCode = $_GET['vnp_TxnRef'] ?? '';
        if ($isValid) {
            // Xóa Cart trong Session
            unset($_SESSION[CART_SESSION_KEY]);
            $title = "Đặt hàng thành công";
            $orderSuccess = true;
            ob_start();
            require __DIR__ . "/../../views/client/cart/checkout.php";
            $content = ob_get_clean();
            require __DIR__ . "/../../views/client/layouts/master.php";
            return;
        } else {
            $_SESSION["order_error"] = "Thanh toán qua VNPAY thất bại hoặc bị hủy.";
            header("Location: " . BASE_URL . "/cart/checkout");
            exit;
        }
    }
}
