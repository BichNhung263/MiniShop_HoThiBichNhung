<div class="container">
<h2>Danh sách sản phẩm</h2>
<div class="row">
<?php if (empty($products)): ?>
<div class="col-12">
<div class="alert alert-warning">
Không tìm thấy sản phẩm.
</div>
</div>
<?php else: ?>
<?php foreach ($products as $product): ?>
<div class="col-md-3 mb-4">
<?php require __DIR__ . '/../layouts/product-card.php'; ?>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>