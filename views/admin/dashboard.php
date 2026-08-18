<?php 
$pageTitle = "Dashboard";

require_once __DIR__ . "/../../autoload.php";

use DAO\CategoryDAO;
use DAO\BrandDAO;
use DAO\ProductDAO;
use DAO\CustomerDAO;
use DAO\OrderDAO;

$totalCategories = 0;
$totalBrands = 0;
$totalProducts = 0;
$totalCustomers = 0;
$totalOrders = 0;
$latestProducts = [];
$latestOrders = [];

try {
    $categoryDAO = new \DAO\CategoryDAO();
    $brandDAO = new \DAO\BrandDAO();
    $productDAO = new \DAO\ProductDAO();
    $customerDAO = new \DAO\CustomerDAO();
    $orderDAO = new \DAO\OrderDAO();

    $totalCategories = $categoryDAO->countAll();
    $totalBrands = $brandDAO->countAll();
    $totalProducts = $productDAO->countAll();
    $totalCustomers = $customerDAO->countAll();
    $totalOrders = $orderDAO->countAll();

    $latestProducts = $productDAO->getLatest();
    $latestOrders = $orderDAO->getLatest();
} catch (Exception $e) {
}

ob_start();
?>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="fs-1 text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="text-end">
                        <div class="fs-2 fw-bold text-dark"><?= $totalCustomers ?></div>
                        <div class="text-muted small">Khách hàng</div>
                    </div>
                </div>
                <div class="border-top pt-2 text-center">
                    <a href="/MiniShop_HoThiBichNhung/admin/customer" class="text-decoration-none small text-primary fw-semibold">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="fs-1 text-success">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                    <div class="text-end">
                        <div class="fs-2 fw-bold text-dark"><?= $totalProducts ?></div>
                        <div class="text-muted small">Sản phẩm</div>
                    </div>
                </div>
                <div class="border-top pt-2 text-center">
                    <a href="/MiniShop_HoThiBichNhung/admin/product" class="text-decoration-none small text-success fw-semibold">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="fs-1 text-warning">
                        <i class="bi bi-bag-fill"></i>
                    </div>
                    <div class="text-end">
                        <div class="fs-2 fw-bold text-dark"><?= $totalOrders ?></div>
                        <div class="text-muted small">Đơn hàng</div>
                    </div>
                </div>
                <div class="border-top pt-2 text-center">
                    <a href="/MiniShop_HoThiBichNhung/admin/order" class="text-decoration-none small text-warning fw-semibold">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="fs-1 text-info">
                        <i class="bi bi-folder-fill"></i>
                    </div>
                    <div class="text-end">
                        <div class="fs-2 fw-bold text-dark"><?= $totalCategories ?></div>
                        <div class="text-muted small">Danh mục</div>
                    </div>
                </div>
                <div class="border-top pt-2 text-center">
                    <a href="/MiniShop_HoThiBichNhung/admin/category" class="text-decoration-none small text-info fw-semibold">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="fs-1 text-danger">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="text-end">
                        <div class="fs-2 fw-bold text-dark"><?= $totalBrands ?></div>
                        <div class="text-muted small">Thương hiệu</div>
                    </div>
                </div>
                <div class="border-top pt-2 text-center">
                    <a href="/MiniShop_HoThiBichNhung/admin/brand" class="text-decoration-none small text-danger fw-semibold">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-4">
    <h5 class="fw-bold mb-3">Đơn hàng mới nhất</h5>
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3">Mã đơn</th>
                            <th class="py-3">Khách hàng</th>
                            <th class="py-3">Ngày đặt</th>
                            <th class="py-3">Tổng tiền</th>
                            <th class="py-3 text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($latestOrders)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Chưa có đơn hàng nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($latestOrders as $index => $ord): ?>
                                <tr>
                                    <td class="px-4"><?= $index + 1 ?></td>
                                    <td class="fw-bold text-primary"><?= $ord->orderCode ?></td>
                                    <td class="fw-medium"><?= $ord->customerName ?></td>
                                    <td class="text-muted"><?= !empty($ord->createdAt) ? date('d/m/Y', strtotime($ord->createdAt)) : date('d/m/Y') ?></td>
                                    <td class="fw-bold"><?= number_format($ord->totalAmount, 0, ',', '.') ?> đ</td>
                                    <td class="text-center">
                                        <?php if ($ord->status == 1): ?>
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Hoàn thành</span>
                                        <?php elseif ($ord->status == 0): ?>
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">Chờ xử lý</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Đã hủy</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="mb-4">
    <h5 class="fw-bold mb-3">Sản phẩm mới nhất</h5>
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">Id</th>
                            <th class="py-3">Tên sản phẩm</th>
                            <th class="py-3">Giá bán</th>
                            <th class="py-3">Danh mục</th>
                            <th class="py-3">Thương hiệu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($latestProducts)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Chưa có sản phẩm nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($latestProducts as $index => $pro): ?>
                                <tr>
                                    <td class="px-4"><?= $index + 1 ?></td>
                                    <td class="fw-semibold"><?= $pro->proname ?></td>
                                    <td class="text-danger fw-bold"><?= number_format($pro->price, 0, ',', '.') ?> đ</td>
                                    <td><span class="badge bg-light text-dark border"><?= $pro->cateName ?></span></td>
                                    <td><span class="badge bg-light text-dark border"><?= $pro->brandName ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "layouts/master.php";
?>