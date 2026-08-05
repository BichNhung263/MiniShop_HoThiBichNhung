<?php
$pageTitle = "Dashboard";

require_once __DIR__ . "/../../dao/CategoryDAO.php";
require_once __DIR__ . "/../../dao/BrandDAO.php";
require_once __DIR__ . "/../../dao/ProductDAO.php";
require_once __DIR__ . "/../../dao/CustomerDAO.php";
require_once __DIR__ . "/../../dao/OrderDAO.php";

// Khởi tạo DAO
$categoryDAO = new CategoryDAO();
$brandDAO    = new BrandDAO();
$productDAO  = new ProductDAO();
$customerDAO = new CustomerDAO();
$orderDAO    = new OrderDAO();

// Thống kê tổng số
try { $totalCategories = $categoryDAO->countAll(); } catch (Exception $e) { $totalCategories = 0; }
try { $totalBrands     = $brandDAO->countAll();    } catch (Exception $e) { $totalBrands = 0; }
try { $totalProducts   = $productDAO->countAll();  } catch (Exception $e) { $totalProducts = 0; }
try { $totalCustomers  = $customerDAO->countAll(); } catch (Exception $e) { $totalCustomers = 0; }
try { $totalOrders     = $orderDAO->countAll();    } catch (Exception $e) { $totalOrders = 0; }

// Lấy top 5 mới nhất
try { $latestProducts = $productDAO->getTop5Latest(); } catch (Exception $e) { $latestProducts = []; }
try { $latestOrders   = $orderDAO->getTop5Latest();   } catch (Exception $e) { $latestOrders = []; }

ob_start();
?>

<!-- Tiêu đề trang -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0">Dashboard Quản Trị</h4>
</div>

<!-- 5 CARD THỐNG KÊ -->
<div class="row g-3 mb-4">

    <!-- Danh mục -->
    <div class="col">
        <div class="card text-white bg-primary shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 small text-uppercase fw-bold opacity-75">Danh mục</p>
                    <h3 class="fw-bold mb-0"><?= $totalCategories ?></h3>
                </div>
                <i class="bi bi-folder-fill fs-2 opacity-75"></i>
            </div>
            <div class="card-footer bg-black bg-opacity-10 border-0 text-center py-2">
                <a href="categories/index.php" class="text-white text-decoration-none small">Chi tiết <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

    <!-- Thương hiệu -->
    <div class="col">
        <div class="card text-dark bg-warning shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 small text-uppercase fw-bold opacity-75">Thương hiệu</p>
                    <h3 class="fw-bold mb-0"><?= $totalBrands ?></h3>
                </div>
                <i class="bi bi-star-fill fs-2 opacity-75"></i>
            </div>
            <div class="card-footer bg-black bg-opacity-10 border-0 text-center py-2">
                <a href="brands/index.php" class="text-dark text-decoration-none small">Chi tiết <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

    <!-- Sản phẩm -->
    <div class="col">
        <div class="card text-white bg-success shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 small text-uppercase fw-bold opacity-75">Sản phẩm</p>
                    <h3 class="fw-bold mb-0"><?= $totalProducts ?></h3>
                </div>
                <i class="bi bi-bag-fill fs-2 opacity-75"></i>
            </div>
            <div class="card-footer bg-black bg-opacity-10 border-0 text-center py-2">
                <a href="products/index.php" class="text-white text-decoration-none small">Chi tiết <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

    <!-- Khách hàng -->
    <div class="col">
        <div class="card text-white bg-info shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 small text-uppercase fw-bold opacity-75">Khách hàng</p>
                    <h3 class="fw-bold mb-0"><?= $totalCustomers ?></h3>
                </div>
                <i class="bi bi-people-fill fs-2 opacity-75"></i>
            </div>
            <div class="card-footer bg-black bg-opacity-10 border-0 text-center py-2">
                <a href="customers/index.php" class="text-white text-decoration-none small">Chi tiết <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

    <!-- Đơn hàng -->
    <div class="col">
        <div class="card text-white bg-danger shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 small text-uppercase fw-bold opacity-75">Đơn hàng</p>
                    <h3 class="fw-bold mb-0"><?= $totalOrders ?></h3>
                </div>
                <i class="bi bi-receipt-cutoff fs-2 opacity-75"></i>
            </div>
            <div class="card-footer bg-black bg-opacity-10 border-0 text-center py-2">
                <a href="orders/index.php" class="text-white text-decoration-none small">Chi tiết <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

</div>

<!-- 2 BẢNG DỮ LIỆU -->
<div class="row g-4">

    <!-- 5 Sản phẩm mới nhất -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                <h6 class="m-0 fw-bold"><i class="bi bi-box-seam me-2 text-success"></i>05 Sản phẩm mới nhất</h6>
                <a href="products/index.php" class="btn btn-sm btn-outline-secondary">Tất cả</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã SP</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá bán</th>
                            <th>SL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($latestProducts)): ?>
                            <?php foreach ($latestProducts as $p): ?>
                                <tr>
                                    <td class="fw-semibold">#<?= $p->id ?></td>
                                    <td><?= htmlspecialchars($p->proname) ?></td>
                                    <td class="text-danger fw-bold"><?= number_format($p->price, 0, ',', '.') ?> đ</td>
                                    <td><span class="badge bg-secondary"><?= $p->quantity ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Chưa có sản phẩm nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5 Đơn hàng mới nhất -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                <h6 class="m-0 fw-bold"><i class="bi bi-cart-check me-2 text-primary"></i>05 Đơn hàng mới nhất</h6>
                <a href="orders/index.php" class="btn btn-sm btn-outline-secondary">Tất cả</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($latestOrders)): ?>
                            <?php foreach ($latestOrders as $ord): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($ord->orderCode) ?></td>
                                    <td><?= htmlspecialchars($ord->customerName ?? 'Khách hàng') ?></td>
                                    <td class="text-danger fw-bold"><?= number_format($ord->totalAmount, 0, ',', '.') ?> đ</td>
                                    <td>
                                        <?php if ($ord->status == 1): ?>
                                            <span class="badge bg-success">Hoàn thành</span>
                                        <?php elseif ($ord->status == 0): ?>
                                            <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Đã hủy</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Chưa có đơn hàng nào.</td>
                            </tr>
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
