<div class="container my-4">
    <?php if (empty($product)): ?>
        <div class="alert alert-danger text-center my-5 py-4">
            <h4 class="alert-heading fw-bold mb-2">Sản phẩm không tồn tại!</h4>
            <p class="mb-3 text-muted">Sản phẩm bạn đang tìm kiếm không tồn tại hoặc đã bị xóa khỏi hệ thống.</p>
            <a href="<?= BASE_URL ?>" class="btn btn-primary px-4">
                <i class="bi bi-arrow-left me-1"></i> Quay về trang chủ
            </a>
        </div>
    <?php else: ?>
        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none">Trang chủ</a></li>
                <?php if (!empty($product->cateName)): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/category/<?= $product->slug ?>" class="text-decoration-none">
                            <?= htmlspecialchars($product->cateName) ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product->proname) ?></li>
            </ol>
        </nav>

        <!-- CHI TIẾT SẢN PHẨM -->
        <div class="row g-4 mb-5">
            <!-- HÌNH ẢNH SẢN PHẨM -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-3 text-center">
                    <?php if (!empty($product->image)): ?>
                        <img src="<?= PRODUCT_IMAGE_URL . htmlspecialchars($product->image) ?>" 
                             class="img-fluid rounded" 
                             alt="<?= htmlspecialchars($product->proname) ?>"
                             style="max-height: 400px; object-fit: contain;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 350px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- THÔNG TIN SẢN PHẨM -->
            <div class="col-md-7">
                <h2 class="fw-bold mb-3"><?= htmlspecialchars($product->proname) ?></h2>

                <!-- BRAND & CATEGORY -->
                <div class="mb-3 text-muted small d-flex gap-3">
                    <?php if (!empty($product->cateName)): ?>
                        <span><i class="bi bi-folder me-1"></i> <strong>Danh mục:</strong> <?= htmlspecialchars($product->cateName) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($product->brandName)): ?>
                        <span><i class="bi bi-tag me-1"></i> <strong>Thương hiệu:</strong> <?= htmlspecialchars($product->brandName) ?></span>
                    <?php endif; ?>
                </div>

                <!-- GIÁ -->
                <div class="p-3 bg-light rounded mb-4">
                    <?php $discountPrice = $product->discountPrice ?? 0; ?>
                    <?php if ($discountPrice > 0): ?>
                        <div class="d-flex align-items-baseline gap-3">
                            <span class="fs-2 fw-bold text-danger"><?= number_format($discountPrice) ?> ₫</span>
                            <del class="fs-5 text-muted"><?= number_format($product->price) ?> ₫</del>
                            <span class="badge bg-danger">Giảm giá</span>
                        </div>
                    <?php else: ?>
                        <span class="fs-2 fw-bold text-dark"><?= number_format($product->price) ?> ₫</span>
                    <?php endif; ?>
                </div>

                <!-- MÔ TẢ NGẮN / TRẠNG THÁI -->
                <div class="mb-4">
                    <p class="mb-2"><strong>Trạng thái:</strong> 
                        <?php if ($product->quantity > 0): ?>
                            <span class="badge bg-success">Còn hàng (<?= $product->quantity ?>)</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Hết hàng</span>
                        <?php endif; ?>
                    </p>
                </div>

                <!-- MÔ TẢ CHI TIẾT -->
                <?php if (!empty($product->description)): ?>
                    <div class="mb-4">
                        <h5 class="fw-bold border-bottom pb-2">Mô tả sản phẩm</h5>
                        <div class="text-secondary lh-base">
                            <?= nl2br(htmlspecialchars($product->description)) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- THAO TÁC MUA HÀNG -->
                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-primary btn-lg px-4" onclick="addToCart(<?= $product->id ?>)">
                        <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ hàng
                    </button>
                    <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary btn-lg px-4">
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- SẢN PHẨM LIÊN QUAN -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="mt-5 border-top pt-4">
                <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
                <div class="row">
                    <?php foreach ($relatedProducts as $product): ?>
                        <div class="col-md-3 col-6 mb-4">
                            <?php require __DIR__ . '/../layouts/product-card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
