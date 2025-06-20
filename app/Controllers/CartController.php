<?php
namespace Controllers;

use Models\Menu;

class CartController
{
    public function showCart(): void
    {
        $cart = $_SESSION['cart'] ?? [];
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/cart.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }

    public function addItem(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id && Menu::find($id)) {
            if (!isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id] = 0;
            }
            $_SESSION['cart'][$id]++;
        }
        header('Location: /cart');
        exit;
    }
}
