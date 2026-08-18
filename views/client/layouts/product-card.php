<div class="card h-100">
    <img src="<?= PRODUCT_IMAGE_URL . $product->image ?>"
        class="card-img-top"
        alt="<?= $product->proname ?>"
        style="height: 220px; object-fit: contain;">
    <div class="card-body">
        <h5><?= $product->proname ?></h5>
        <del><?= number_format($product->price) ?></del>
        <p class="text-danger">
            <?= number_format($product->discountprice) ?> đ
        </p>
        <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>/product/<?= $product->slug ?>" class="btn btn-outline-secondary btn-sm" title="Xem chi tiết">
                <i class="bi bi-eye"></i>
            </a>
            <button type="button" class="btn btn-primary btn-sm" title="Mua hàng">
                <i class="bi bi-cart-plus"></i>
            </button>
        </div>
    </div>
</div>
