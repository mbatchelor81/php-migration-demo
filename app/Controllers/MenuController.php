<?php
namespace Controllers;

use Models\Menu;

class MenuController
{


    public function showMenu(): void
    {
        $items = Menu::all();
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/menu.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }
}
