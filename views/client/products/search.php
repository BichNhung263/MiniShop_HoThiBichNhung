<div class="container my-4">
    <?php if (empty($keyword)): ?>
        <div class="alert alert-info text-center my-5 py-4">
            <i class="bi bi-search fs-1 text-primary d-block mb-3"></i>
            <h4 class="fw-bold mb-2">Vui lòng nhập từ khóa tìm kiếm</h4>
            <p class="text-muted mb-0">Bạn có thể tìm kiếm theo tên sản phẩm, danh mục hoặc thương hiệu.</p>
        </div>
    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h3 class="fw-bold mb-0">Kết quả tìm kiếm</h3>
            <span class="text-muted">Từ khóa: <strong class="text-primary">"<?= $keyword ?>"</strong></span>
        </div>

        <?php if (empty($products)): ?>
            <div class="alert alert-warning text-center my-5 py-4">
                <i class="bi bi-exclamation-circle fs-1 text-warning d-block mb-3"></i>
                <h4 class="fw-bold mb-2">Không tìm thấy sản phẩm!</h4>
                <p class="text-muted mb-0">Không có sản phẩm nào phù hợp với từ khóa "<strong><?= $keyword ?></strong>". Vui lòng thử lại với từ khóa khác.</p>
            </div>
        <?php else: ?>
            <p class="text-muted mb-4">Tìm thấy <strong><?= count($products) ?></strong> sản phẩm phù hợp.</p>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-3 col-6 mb-4">
                        <?php require __DIR__ . '/../layouts/product-card.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>