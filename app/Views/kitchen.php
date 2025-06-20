<h2>Kitchen Queue</h2>
<?php if (!$queue): ?>
    <p>No orders in queue.</p>
<?php else: ?>
<table class="cart-table">
    <thead>
        <tr><th>Order #</th><th>Items</th><th>Action</th></tr>
    </thead>
    <tbody>
    
    <?php foreach ($queue as $orderId => $items): ?>
        <tr>
            <td>#<?= $orderId ?></td>
            <td>
                <?php foreach ($items as $row): ?>
                    <?= htmlspecialchars($row['name']) ?> x <?= $row['quantity'] ?><br>
                <?php endforeach; ?>
            </td>
            <td><a class="btn" href="/kitchen/ready?id=<?= $orderId ?>">Mark Ready</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
