<?php require_once 'config.php';
if (empty($_SESSION['cart'])) header('Location: index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Twin Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="index.php">🍽️ Twin Star</a>
        <a href="cart.php">🛒 Cart</a>
        <a href="track_order.php">📦 Track Order</a>
    </nav>
</header>
<main>
    <h1>Checkout</h1>
    <form class="checkout-form" action="place_order.php" method="POST">
        <div class="form-group"><label>Full Name *</label><input type="text" name="customer_name" required></div>
        <div class="form-group"><label>Phone Number *</label><input type="tel" name="customer_phone" required></div>
        <div class="form-group"><label>Email (optional)</label><input type="email" name="customer_email"></div>
        <div class="form-group"><label>Delivery Address *</label><textarea name="customer_address" rows="3" required></textarea></div>
        <div class="form-group"><label>Payment Method</label><select name="payment_method"><option>Cash on Delivery</option></select></div>
        <button type="submit" class="btn">Place Order</button>
    </form>
</main>
<script src="script.js"></script>
</body>
</html>