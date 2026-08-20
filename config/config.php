<?php
define("BASE_URL", "/MiniShop_HoThiBichNhung");
define("PRODUCT_IMAGE_URL", BASE_URL . "/uploads/products/");
define("CART_SESSION_KEY", "cart");

// Cấu hình VNPAY Sandbox 
define("VNP_TMN_CODE", "0L6A1BUH");
define("VNP_HASH_SECRET", "M8NCTDCRIK19Y2RPT9LLSP0GO3XKN82O");
define("VNP_URL", "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html");
define("VNP_RETURN_URL", "http://localhost/MiniShop_HoThiBichNhung/cart/vnpay_return");

