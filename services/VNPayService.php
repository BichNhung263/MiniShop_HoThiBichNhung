<?php

namespace Services;

class VNPayService
{
    /**
     * Tạo URL chuyển sang cổng thanh toán VNPAY
     */
    public static function createPaymentUrl(string $orderCode, float $totalAmount): string
    {
        $vnpUrl = VNP_URL;
        $vnp_Returnurl = VNP_RETURN_URL;
        $vnp_TmnCode = VNP_TMN_CODE;
        $vnp_HashSecret = VNP_HASH_SECRET;

        $vnp_TxnRef = $orderCode;
        $vnp_OrderInfo = "ThanhToanDonHang" . $orderCode;
        $vnp_OrderType = "other";
        $vnp_Amount = (int)($totalAmount * 100);
        $vnp_Locale = "vn";
        $vnp_IpAddr = "127.0.0.1";

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = date('YmdHis');
        // Nếu đồng hồ máy tính bị chỉnh sang năm 2026 -> chuyển lại năm 2024 để khớp với Server VNPAY
        if (str_starts_with($now, '2026')) {
            $now = '2024' . substr($now, 4);
        }
        $vnp_CreateDate = $now;
        $inputData = array(
            "vnp_Amount" => $vnp_Amount,
            "vnp_BankCode" => "NCB",
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_Version" => "2.1.0",
        );
        ksort($inputData);
        $hashData = http_build_query($inputData, '', '&', PHP_QUERY_RFC3986);
        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnp_Url = $vnpUrl . "?" . $hashData . "&vnp_SecureHash=" . $vnpSecureHash;

        return $vnp_Url;
    }

    public static function validateReturn(array $queryParams): bool
    {
        $vnp_SecureHash = $queryParams['vnp_SecureHash'] ?? '';
        $vnp_HashSecret = VNP_HASH_SECRET;

        $inputData = array();
        foreach ($queryParams as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        return (hash_equals($secureHash, $vnp_SecureHash) && ($queryParams['vnp_ResponseCode'] ?? '') === '00');
    }
}
