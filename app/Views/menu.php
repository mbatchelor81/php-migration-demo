<h2>Menu</h2>
<div class="grid">
<?php foreach ($items as $item): ?>
    <div class="card">
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <p class="price">$<?= number_format($item['price'], 2) ?></p>
        <a class="btn" href="/cart/add?id=<?= $item['id'] ?>">Add to Cart</a>
    </div>
<?php endforeach; ?>
</div>
