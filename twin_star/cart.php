<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Twin Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="index.php">🍽️ Twin Star</a>
        <a href="cart.php">🛒 Cart (<span id="cart-count">0</span>)</a>
        <a href="track_order.php">📦 Track Order</a>
    </nav>
</header>
<main>
    <h1>Your Cart</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <p style="text-align: center;">Cart is empty. <a href="index.php">Continue shopping</a></p>
    <?php else: ?>
        <div class="cart-table">
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $item_id => $quantity):
                $stmt = $conn->prepare("SELECT menu_id, food_name, price FROM menu WHERE menu_id = ?");
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
                $item = $stmt->get_result()->fetch_assoc();
                if ($item):
                    $item_total = $item['price'] * $quantity;
                    $total += $item_total;
            ?>
            <div class="cart-item">
                <div class="cart-item-details"><strong><?php echo htmlspecialchars($item['food_name']); ?></strong></div>
                <div class="cart-item-price"><?php echo CURRENCY; ?> <?php echo number_format($item['price'], 2); ?></div>
                <div class="cart-item-actions">
                    <input type="number" class="cart-quantity" data-id="<?php echo $item['menu_id']; ?>" value="<?php echo $quantity; ?>" min="1">
                    <button class="btn btn-secondary remove-item" data-id="<?php echo $item['menu_id']; ?>">Remove</button>
                </div>
                <div class="cart-item-total"><?php echo CURRENCY; ?> <?php echo number_format($item_total, 2); ?></div>
            </div>
            <?php endif; endforeach; ?>
        </div>
        <div class="cart-summary">
            <h3>Total: <?php echo CURRENCY; ?> <?php echo number_format($total, 2); ?></h3>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</main>
<script src="script.js"></script>
</body>
</html>