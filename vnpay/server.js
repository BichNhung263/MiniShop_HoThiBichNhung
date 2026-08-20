import express from "express";
import cors from "cors";
import moment from "moment";
import "dotenv/config";
import { VNPay } from 'vnpay';

const app = express();
app.use(cors());
app.use(express.json());

const vnpay = new VNPay({
    tmnCode: process.env.VNP_TMN_CODE,
    secureSecret: process.env.VNP_HASH_SECRET,
    vnpayHost: 'https://sandbox.vnpayment.vn',
});

app.get("/payment", async (req, res) => {
    try {
        const { amount, orderId } = req.query;
        
        const paymentUrl = vnpay.buildPaymentUrl({
            vnp_Amount: Number(amount),
            vnp_IpAddr: '127.0.0.1',
            vnp_TxnRef: orderId + moment().format('HHmmss'),
            vnp_OrderInfo: 'ThanhToanDonHang' + orderId,
            vnp_OrderType: 'other',
            vnp_ReturnUrl: 'http://localhost:5173/vnpay-return',
            vnp_Locale: 'vn',
            vnp_CreateDate: moment().format('YYYYMMDDHHmmss'),
            vnp_BankCode: 'NCB',
        });

        console.log(">>> URL MỚI TẠO:", paymentUrl);
        res.json({ paymentUrl });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.listen(3000, () => console.log("Server VNPay chuẩn đã sẵn sàng tại port 3000"));
