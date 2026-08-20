<div class="container my-4">
    <!-- BANNER SLIDER -->
    <div id="homeBanner" class="carousel slide mb-5 rounded-4 overflow-hidden shadow" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#homeBanner" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#homeBanner" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#homeBanner" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <!-- SLIDE 1 -->
            <div class="carousel-item active banner-slide-1">
                <div class="banner-content">
                    <div>
                        <div class="banner-icon"></div>
                        <span class="badge mb-3 px-3 py-2 fw-semibold banner-badge-yellow">CHÀO MỪNG BẠN ĐẾN</span>
                        <h1 class="fw-bold mb-3 banner-title">MiniShop – Yêu Thú Cưng</h1>
                        <p class="mb-4 banner-desc">Cửa hàng chuyên cung cấp thú cưng &amp; phụ kiện chính hãng chất lượng cao</p>
                        <div class="btn btn-warning btn-lg fw-bold px-5 rounded-pill shadow">
                            <i class="bi bi-fire me-2"></i>Xem ưu đãi ngay
                        </div>
                    </div>
                </div>
            </div>
            <!-- SLIDE 2 -->
            <div class="carousel-item banner-slide-2">
                <div class="banner-content">
                    <div>
                        <div class="banner-icon"></div>
                        <span class="badge mb-3 px-3 py-2 fw-semibold banner-badge-white">KHUYẾN MÃI HOT</span>
                        <h1 class="fw-bold mb-3 banner-title">Giảm Giá Siêu Hấp Dẫn</h1>
                        <p class="mb-4 banner-desc">Ưu đãi đặc biệt dành riêng cho thú cưng yêu quý của bạn – Đừng bỏ lỡ!</p>
                        <div class="btn btn-light btn-lg fw-bold px-5 rounded-pill shadow text-primary">
                            <i class="bi bi-bag-check me-2"></i>Mua ngay
                        </div>
                    </div>
                </div>
            </div>
            <!-- SLIDE 3 -->
            <div class="carousel-item banner-slide-3">
                <div class="banner-content">
                    <div>
                        <div class="banner-icon"></div>
                        <span class="badge mb-3 px-3 py-2 fw-semibold banner-badge-white">HÀNG MỚI VỀ</span>
                        <h1 class="fw-bold mb-3 banner-title">Sản Phẩm Mới Nhất</h1>
                        <p class="mb-4 banner-desc">Cập nhật liên tục những sản phẩm mới nhất – chất lượng tốt nhất cho boss nhà bạn</p>
                        <div class="btn btn-light btn-lg fw-bold px-5 rounded-pill shadow text-success">
                            <i class="bi bi-stars me-2"></i>Khám phá ngay
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeBanner" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeBanner" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <!-- DANH MỤC NỔI BẬT -->
    <h2 class="fw-bold mb-4">Danh mục nổi bật</h2>
    <div class="row mb-5">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-3 col-6 mb-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title mb-0">
                            <a href="<?= BASE_URL ?>/category/<?= $category->slug ?>" class="text-decoration-none text-dark fw-semibold">
                                <?= ($category->catename) ?>
                            </a>
                        </h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- SẢN PHẨM GIẢM GIÁ -->
    <h2 id="discount-section" class="fw-bold mb-3 mt-2">Sản phẩm giảm giá</h2>
    <div class="row mb-5">
        <?php foreach ($discountProducts as $product): ?>
            <div class="col-md-3 col-6 mb-4">
                <?php require __DIR__ . '/../layouts/product-card.php'; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- SẢN PHẨM MỚI NHẤT -->
    <h2 id="new-section" class="fw-bold mb-3">Sản phẩm mới nhất</h2>
    <div class="row mb-4">
        <?php foreach ($newProducts as $product): ?>
            <div class="col-md-3 col-6 mb-4">
                <?php require __DIR__ . '/../layouts/product-card.php'; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>