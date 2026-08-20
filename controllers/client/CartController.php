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
        $fullname      = trim($_POST["fullname"] ?? "");
        $email         = trim($_POST["email"] ?? ($_SESSION["client_user"]["email"] ?? ""));
        $phone         = trim($_POST["phone"] ?? "");
        $address       = trim($_POST["address"] ?? "");
        $note          = trim($_POST["note"] ?? "");
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
            //Lưu Order
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
            // Gửi email xác nhận đơn hàng & lưu nhật ký email_logs
            $this->sendOrderSuccessEmail($email, $fullname, $orderCode, $total, $paymentMethod);
            if ($paymentMethod === "vnpay") {
                // Lưu session tạm để vnpay_return dùng lại gửi mail hoặc hiển thị
                $_SESSION['pending_vnpay_order'] = [
                    'email' => $email,
                    'fullname' => $fullname,
                    'total' => $total
                ];
                $vnpayUrl = \Services\VNPayService::createPaymentUrl($orderCode, $total);
                header("Location: " . $vnpayUrl);
                exit;
            }
            // Nếu chọn COD -> Xóa Cart trong Session & hiển thị màn hình thành công
            unset($_SESSION[CART_SESSION_KEY]);
            $title = "Đặt hàng thành công";
            $orderSuccess = true;
            ob_start();
            require __DIR__ . "/../../views/client/cart/checkout.php";
            $content = ob_get_clean();
            require __DIR__ . "/../../views/client/layouts/master.php";
            return;
        } catch (\Exception $e) {
            // Lỗi -> rollback
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
            // Gửi email xác nhận thanh toán VNPAY thành công
            if (!empty($_SESSION['pending_vnpay_order'])) {
                $info = $_SESSION['pending_vnpay_order'];
                $this->sendOrderSuccessEmail($info['email'], $info['fullname'], $orderCode, $info['total'], 'vnpay');
                unset($_SESSION['pending_vnpay_order']);
            }
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
    // Hàm phụ trợ gửi Email THẬT qua Gmail SMTP và Lưu Nhật Ký email_logs
    private function sendOrderSuccessEmail(string $email, string $fullname, string $orderCode, float $total, string $paymentMethod)
    {
        if (empty($email)) return;
        $methodName = ($paymentMethod === 'vnpay') ? 'VNPAY Online' : 'Thanh toán khi nhận hàng (COD)';
        $subject = "Xác nhận đơn hàng #" . $orderCode . " - MiniShop";
        $body = "Xin chào " . $fullname . ",\n\n"
              . "Cảm ơn bạn đã mua sắm tại MiniShop!\n"
              . "--------------------------------------------------\n"
              . "Mã đơn hàng: " . $orderCode . "\n"
              . "Phương thức thanh toán: " . $methodName . "\n"
              . "Tổng tiền thanh toán: " . number_format($total, 0, ',', '.') . " VNĐ\n"
              . "--------------------------------------------------\n"
              . "Đơn hàng của bạn đã được xác nhận thành công và đang được xử lý.\n\n"
              . "Trân trọng,\n"
              . "Đội ngũ MiniShop";
        //Thử gửi qua Gmail SMTP trực tiếp
        $sent = $this->sendGmailSMTP($email, $subject, $body);
        //Dự phòng bằng mail() mặc định
        if (!$sent) {
            $headers = "From: bichnhung263@gmail.com\r\n"
                     . "Reply-To: bichnhung263@gmail.com\r\n"
                     . "Content-Type: text/plain; charset=UTF-8\r\n";
            @mail($email, $subject, $body, $headers);
        }
        //Lưu lịch sử vào bảng email_logs trong CSDL
        try {
            $db = $this->customerDAO->getConnection();
            $status = $sent ? 'sent' : 'logged';
            $stmt = $db->prepare("INSERT INTO email_logs (recipient_email, subject, body, status) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssss", $email, $subject, $body, $status);
                $stmt->execute();
            }
        } catch (\Exception $e) {
            // Không ngắt luồng nếu lưu log lỗi
        }
    }
    // Gửi Gmail SMTP socket trực tiếp không qua file trung gian
    private function sendGmailSMTP(string $toEmail, string $subject, string $bodyContent): bool
    {
        $user = "bichnhung263@gmail.com";
        $pass = str_replace(' ', '', "ggxr wodx oazs fiub"); 
        if (empty($pass)) return false;
        try {
            $socket = @fsockopen("ssl://smtp.gmail.com", 465, $errno, $errstr, 15);
            if (!$socket) return false;
            $read = function($sock) {
                $res = "";
                while ($line = fgets($sock, 512)) {
                    $res .= $line;
                    if (substr($line, 3, 1) == ' ') break;
                }
                return $res;
            };
            $read($socket);
            fputs($socket, "EHLO localhost\r\n");
            $read($socket);
            fputs($socket, "AUTH LOGIN\r\n");
            $read($socket);
            fputs($socket, base64_encode($user) . "\r\n");
            $read($socket);
            fputs($socket, base64_encode($pass) . "\r\n");
            $authRes = $read($socket);
            if (strpos($authRes, '235') === false) { 
                fclose($socket); 
                return false; 
            }
            fputs($socket, "MAIL FROM: <$user>\r\n");
            $read($socket);
            fputs($socket, "RCPT TO: <$toEmail>\r\n");
            $read($socket);
            fputs($socket, "DATA\r\n");
            $read($socket);
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: MiniShop <$user>\r\n";
            $headers .= "To: <$toEmail>\r\n";
            $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $msg = $headers . "\r\n" . nl2br($bodyContent) . "\r\n.\r\n";
            fputs($socket, $msg);
            $read($socket);
            fputs($socket, "QUIT\r\n"); 
            fclose($socket);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
