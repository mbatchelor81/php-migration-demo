<h2>Your Cart</h2>
<?php if (!$cart): ?>
    <p>Your cart is empty. <a href="/menu">Browse the menu</a>.</p>
<?php else: ?>
<table class="cart-table">
    <thead>
        <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
    </thead>
    <tbody>
        <?php $total = 0; ?>
        <?php foreach ($cart as $id => $qty): ?>
            <?php $item = \Models\Menu::find($id); if (!$item) continue; $line = $item['price'] * $qty; $total += $line; ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $qty ?></td>
                <td>$<?= number_format($line, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p class="total">Total: $<?= number_format($total, 2) ?></p>
<a class="btn primary" href="/checkout">Checkout</a>
<?php endif; ?>
