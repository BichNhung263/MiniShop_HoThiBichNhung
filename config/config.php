<?php
define("BASE_URL", "/MiniShop_HoThiBichNhung");
define("PRODUCT_IMAGE_URL", BASE_URL . "/uploads/products/");
define("CART_SESSION_KEY", "cart");

// Cấu hình VNPAY Sandbox
define("VNP_TMN_CODE",    "S1A37MRL");                 
define("VNP_HASH_SECRET", "JJRMXXVYRCRWVKRQKTMGRJGGAMYYJUKJ"); 
define("VNP_URL",         "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html");
define("VNP_RETURN_URL",  "http://localhost/MiniShop_HoThiBichNhung/cart/vnpay_return");

