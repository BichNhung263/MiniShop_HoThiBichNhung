<div class="container my-5">
    <h2 class="fw-bold mb-4">Liên hệ với chúng tôi</h2>
    <div class="row g-4">
        <!-- THÔNG TIN LIÊN HỆ -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Thông tin liên hệ</h5>
                    <p><i class="bi bi-geo-alt-fill text-danger me-2"></i> 123 Đường ABC, TP. Hồ Chí Minh</p>
                    <p><i class="bi bi-telephone-fill text-success me-2"></i> 0123456789/p>
                    <p><i class="bi bi-envelope-fill text-primary me-2"></i> minishop@gmail.com</p>
                    <p><i class="bi bi-clock-fill text-warning me-2"></i> Thứ 2 – Thứ 7: 8:00 – 20:00</p>
                    <hr>
                    <p class="mb-2 fw-semibold">Mạng xã hội:</p>
                    <a href="#" class="btn btn-outline-primary btn-sm me-2">
                        <i class="bi bi-facebook me-1"></i>Facebook
                    </a>
                    <a href="#" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-instagram me-1"></i>Instagram
                    </a>
                </div>
            </div>
        </div>
        <!-- FORM LIÊN HỆ -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Gửi tin nhắn cho chúng tôi</h5>
                    <?php if (!empty($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.
                        </div>
                    <?php endif; ?>
                    <form action="<?= BASE_URL ?>/contact" method="GET">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Nhập họ tên của bạn" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="example@gmail.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" placeholder="0901 234 567">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Nhập nội dung tin nhắn..." required></textarea>
                        </div>
                        <input type="hidden" name="success" value="1">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send me-2"></i>Gửi tin nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- BẢN ĐỒ -->
    <div class="mt-5">
        <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Bản đồ cửa hàng</h5>
        <div class="rounded-3 overflow-hidden shadow-sm" style="height: 380px;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.444!2d106.6983!3d10.7769!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDQ2JzM3LjAiTiAxMDbCsDQxJzUzLjkiRQ!5e0!3m2!1svi!2s!4v1600000000000!5m2!1svi!2s"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>
