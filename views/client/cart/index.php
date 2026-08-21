<div class="container my-4">
    <h2 class="fw-bold mb-4">Giỏ hàng của bạn</h2>
    <?php if (empty($cart)): ?>
        <div class="alert alert-warning text-center py-4">
            <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
            <h4>Giỏ hàng trống!</h4>
            <p class="mb-3 text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="<?= BASE_URL ?>/" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                        <tr>
                            <td>
                                <img src="<?= PRODUCT_IMAGE_URL . $item['image'] ?>" alt="<?= ($item['productname']) ?>" style="width: 70px; height: 70px; object-fit: contain;">
                            </td>
                            <td class="text-start fw-bold"><?= ($item['productname'] ?? $item['proname'] ?? '') ?></td>
                            <td class="text-danger fw-semibold"><?= number_format($item['price']) ?> đ</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="updateCart(<?= $item['productid'] ?>, <?= $item['quantity'] - 1 ?>)">
                                        -
                                    </button>
                                    <span class="px-2 fw-bold" id="qty-<?= $item['productid'] ?>"><?= $item['quantity'] ?></span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="updateCart(<?= $item['productid'] ?>, <?= $item['quantity'] + 1 ?>)">
                                        +
                                    </button>
                                </div>
                            </td>
                            <td class="text-danger fw-bold" id="total-<?= $item['productid'] ?>"><?= number_format($item['price'] * $item['quantity']) ?> đ</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeCart(<?= $item['productid'] ?>)" title="Xóa">
                                    <i class=" "></i> Xóa
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded border">
            <div>
                <a href="<?= BASE_URL ?>/" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
                </a>
            </div>
            <div class="text-end">
                <h4 class="fw-bold mb-2">Tổng tiền: <span class="text-danger" id="cartTotalSum"><?= number_format($total) ?> đ</span></h4>
                <a href="<?= BASE_URL ?>/cart/checkout" class="btn btn-success btn-lg px-4">
                    <i class=" "></i> Đặt hàng
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
