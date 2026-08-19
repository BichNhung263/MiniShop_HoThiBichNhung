<div class="container my-4">
    <h2 class="mb-4">Danh mục nổi bật</h2>
    <div class="row mb-5">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-3 col-6 mb-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title mb-0">
                            <a href="#" class="text-decoration-none text-dark fw-semibold">
                                <?= ($category->catename) ?>
                            </a>
                        </h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="mb-3 mt-2">Sản phẩm giảm giá</h2>
    <div class="row mb-5">
        <?php foreach ($discountProducts as $product): ?>
            <div class="col-md-3 col-6 mb-4">
                <?php require __DIR__ . '/../layouts/product-card.php'; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="mb-3">Sản phẩm mới nhất</h2>
    <div class="row mb-4">
        <?php foreach ($newProducts as $product): ?>
            <div class="col-md-3 col-6 mb-4">
                <?php require __DIR__ . '/../layouts/product-card.php'; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>