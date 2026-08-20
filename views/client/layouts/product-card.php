<div class="card h-100">
    <img src="<?= PRODUCT_IMAGE_URL . $product->image ?>"
        class="card-img-top"
        alt="<?= $product->proname ?>"
        style="height: 220px; object-fit: contain;">
    <div class="card-body">
        <h5><?= $product->proname ?></h5>
        <?php if ($product->discountPrice > 0): ?>
            <del><?= number_format($product->price) ?></del>
            <p class="text-danger">
                <?= number_format($product->discountPrice) ?> đ
            </p>
        <?php else: ?>
            <p class="text-danger">
                <?= number_format($product->price) ?> đ
            </p>
        <?php endif; ?>
        <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>/product/<?= $product->slug ?>" class="btn btn-outline-secondary btn-sm" title="Xem chi tiết">
                <i class="bi bi-eye"></i>
            </a>
            <button type="button" class="btn btn-primary btn-add-cart"
                data-productid="<?= $product->id ?>">
                <i class="fa-solid fa-cart-plus"></i> 🛒
            </button>
        </div>
    </div>
</div>