<div class="container my-4" style="max-width: 600px;">
    <?php if (!empty($orderSuccess)): ?>
        <div class="card border-success shadow-sm p-4 text-center">
            <div class="text-success display-3 mb-3">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h3 class="fw-bold text-success mb-3">Đặt hàng thành công!</h3>
            <p class="fs-6 text-secondary mb-3">Cảm ơn quý khách đã mua sắm tại MiniShop.</p>
            <div class="alert alert-light border my-3">
                Mã đơn hàng: <strong class="text-primary fs-5"><?= ($orderCode ?? "") ?></strong>
            </div>
            <div class="mt-4 d-flex justify-content-center gap-3">
                <a href="<?= BASE_URL ?>/" class="btn btn-primary px-4">
                    <i class="bi bi-house me-1"></i> Trang chủ
                </a>
            </div>
        </div>
    <?php elseif (empty($cart)): ?>
        <h2 class="fw-bold mb-4">Đặt hàng</h2>
        <div class="alert alert-warning text-center">
            <h4>Giỏ hàng trống!</h4>
            <a href="<?= BASE_URL ?>/" class="btn btn-primary mt-2">
                <i class="bi bi-cart"></i> Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <h2 class="fw-bold mb-4">Đặt hàng</h2>

        <?php
        // Lấy thông tin từ Session nếu đã đăng nhập
        $clientUser = $_SESSION["client_user"] ?? null;
        ?>

        <?php if (!$clientUser): ?>
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle me-1"></i>
                Bạn có thể <a href="<?= BASE_URL ?>/login" class="alert-link">Đăng nhập</a> tại đây.
            </div>
        <?php else: ?>
            <div class="alert alert-success mb-3">
                <i class="bi bi-person-check-fill me-1"></i>
                Xin chào, <strong><?= ($clientUser["fullname"]) ?></strong>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/cart/checkout" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                <input type="text" name="fullname" class="form-control"
                    placeholder="Nhập họ tên"
                    value="<?= ($clientUser["fullname"] ?? "") ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                <input type="text" name="email" class="form-control"
                    placeholder="Nhập email"
                    value="<?= ($clientUser["email"] ?? "") ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control"
                    placeholder="Nhập số điện thoại"
                    value="<?= ($clientUser["phone"] ?? "") ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                <input type="text" name="address" class="form-control"
                    placeholder="Nhập địa chỉ nhận hàng"
                    value="<?= ($clientUser["address"] ?? "") ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Ghi chú</label>
                <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú thêm (nếu có)"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Phương thức thanh toán <span class="text-danger">*</span></label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
                    <label class="form-check-label" for="payment_cod">
                        <i class="bi bi-cash-stack me-1 text-success"></i> Thanh toán khi nhận hàng (COD)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_vnpay" value="vnpay">
                    <label class="form-check-label fw-bold text-primary" for="payment_vnpay">
                        <i class="bi bi-credit-card me-1"></i> Thanh toán Online qua VNPAY (ATM / QR Code)
                    </label>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="<?= BASE_URL ?>/cart" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                </a>
                <button type="submit" class="btn btn-success btn-lg px-4">
                    <i class=" "></i> Xác nhận đặt hàng
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>
