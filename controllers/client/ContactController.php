<?php
namespace Controllers\Client;

class ContactController
{
    public function index()
    {
        $title = "Liên hệ";
        ob_start();
        require __DIR__ . "/../../views/client/contact/index.php";
        $content = ob_get_clean();
        require __DIR__ . "/../../views/client/layouts/master.php";
    }
}
