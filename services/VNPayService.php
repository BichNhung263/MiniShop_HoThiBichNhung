<?php

namespace Services;

class VNPayService
{
    /**
     * Lấy IP thực của client
     */
    private static function getClientIp(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
        }
        if ($ip === '::1' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            $ip = '127.0.0.1';
        }
        return $ip;
    }

    /**
     * Tạo URL chuyển sang cổng thanh toán VNPAY (Chuẩn VNPAY v2.1.0)
     */
    public static function createPaymentUrl(string $orderCode, float $totalAmount, string $bankCode = "NCB"): string
    {
        $vnpUrl         = VNP_URL;
        $vnp_Returnurl  = VNP_RETURN_URL;
        $vnp_TmnCode    = trim(VNP_TMN_CODE);
        $vnp_HashSecret = trim(VNP_HASH_SECRET);

        $vnp_TxnRef    = $orderCode;
        $vnp_OrderInfo = "ThanhToanDonHang" . $orderCode;
        $vnp_OrderType = "other";
        $vnp_Amount    = (int)($totalAmount * 100);
        $vnp_Locale    = "vn";
        $vnp_IpAddr    = self::getClientIp();

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $vnp_CreateDate = date('YmdHis');

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => $vnp_Amount,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $vnp_IpAddr,
            "vnp_Locale"     => $vnp_Locale,
            "vnp_OrderInfo"  => $vnp_OrderInfo,
            "vnp_OrderType"  => $vnp_OrderType,
            "vnp_ReturnUrl"  => $vnp_Returnurl,
            "vnp_TxnRef"     => $vnp_TxnRef
        ];

        if (!empty($bankCode)) {
            $inputData['vnp_BankCode'] = $bankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnpUrl . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xác thực kết quả trả về từ VNPAY (Chuẩn VNPAY v2.1.0)
     */
    public static function validateReturn(array $queryParams): bool
    {
        $vnp_SecureHash = $queryParams['vnp_SecureHash'] ?? '';
        $vnp_HashSecret = trim(VNP_HASH_SECRET);

        $inputData = [];
        foreach ($queryParams as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
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

        return hash_equals(strtolower($secureHash), strtolower($vnp_SecureHash))
            && ($queryParams['vnp_ResponseCode'] ?? '') === '00';
    }
}
