<?php
namespace Composers;
use DAO\CategoryDAO;
use DAO\BrandDAO;
use Config\Cache;

class HeaderComposer
{
    public static function compose()
    {
        return Cache::remember('header_data', 300, function () {
            $categoryDAO = new CategoryDAO();
            $brandDAO = new BrandDAO();

            return [
                'categories' => $categoryDAO->getByLimit(3),
                'brands'     => $brandDAO->getByLimit(3)
            ];
        });
    }
}
