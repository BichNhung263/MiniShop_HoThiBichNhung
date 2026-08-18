<?php
namespace Controllers\Admin;

class DashboardController {

    public function index()
    {
        require __DIR__ . "/../../views/admin/dashboard.php";
    }
}
