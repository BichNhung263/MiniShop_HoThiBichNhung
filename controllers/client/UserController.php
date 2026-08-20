<?php
namespace Controllers\Client;
use DAO\UserDAO;

class UserController
{
    private UserDAO $userDAO;
    public function __construct()
    {
        $this->userDAO = new UserDAO();
    }
    // Hiển thị form đăng nhập (GET) hoặc xử lý đăng nhập (POST)
    public function login()
    {
        // Nếu đã đăng nhập -> về trang chủ
        if (isset($_SESSION["client_user"])) {
            header("Location: " . BASE_URL . "/");
            exit;
        }
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $title = "Đăng nhập";
            ob_start();
            require __DIR__ . "/../../views/client/login.php";
            $content = ob_get_clean();
            require __DIR__ . "/../../views/client/layouts/master.php";
            return;
        }
        // Xử lý POST
        $username = trim($_POST["username"] ?? "");
        $password = $_POST["password"] ?? "";
        $user = $this->userDAO->findByUsername($username);
        if ($user) {
            $isPlainTextMatch = ($user->password === $password);
            $isHashMatch = password_verify($password, $user->password);
            if ($isPlainTextMatch || $isHashMatch) {
                if ($isPlainTextMatch) {
                    $user->password = password_hash($password, PASSWORD_DEFAULT);
                    $this->userDAO->update($user);
                }
                // Đăng nhập thành công -> lưu vào Session
                $_SESSION["client_user"] = [
                    "id"       => $user->id,
                    "fullname" => $user->fullname,
                    "phone"    => $user->phone,
                    "address"  => $user->address,
                    "email"    => $user->email,
                ];
                header("Location: " . BASE_URL . "/");
                exit;
            }
        }
        // Sai thông tin
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
        $title = "Đăng nhập";
        ob_start();
        require __DIR__ . "/../../views/client/login.php";
        $content = ob_get_clean();
        require __DIR__ . "/../../views/client/layouts/master.php";
    }
    // Đăng xuất
    public function logout()
    {
        unset($_SESSION["client_user"]);
        header("Location: " . BASE_URL . "/");
        exit;
    }
}
