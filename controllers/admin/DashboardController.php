<?php

namespace Controllers\Admin;

use DAO\CategoryDAO;
use DAO\BrandDAO;
use DAO\ProductDAO;
use DAO\CustomerDAO;
use DAO\OrderDAO;
use Exception;

class DashboardController
{
    public function index()
    {
        $pageTitle = "Dashboard";
        $totalCategories = 0;
        $totalBrands = 0;
        $totalProducts = 0;
        $totalCustomers = 0;
        $totalOrders = 0;
        $latestProducts = [];
        $latestOrders = [];
        try {
            $categoryDAO = new CategoryDAO();
            $brandDAO = new BrandDAO();
            $productDAO = new ProductDAO();
            $customerDAO = new CustomerDAO();
            $orderDAO = new OrderDAO();
            $totalCategories = $categoryDAO->countAll();
            $totalBrands = $brandDAO->countAll();
            $totalProducts = $productDAO->countAll();
            $totalCustomers = $customerDAO->countAll();
            $totalOrders = $orderDAO->countAll();
            $latestProducts = $productDAO->getLatest();
            $latestOrders = $orderDAO->getLatest();
        } catch (Exception $e) {
        }

        require __DIR__ . "/../../views/admin/dashboard.php";
    }
}
