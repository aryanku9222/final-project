<?php require_once 'config.php';
$order_id = $_GET['order_id'] ?? 0;
$tracking = $_GET['tracking'] ?? '';
?>
<!DOCTYPE html>
<html>
<head><title>Order Success</title><link rel="stylesheet" href="style.css"></head>
<body>
<header><nav><a href="index.php">🍽️ Twin Star</a></nav></header>
<main>
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1>Order Placed Successfully!</h1>
        <p>Order ID: <?php echo $order_id; ?></p>
        <p>Tracking ID: <strong><?php echo $tracking; ?></strong></p>
        <p>Use this Tracking ID + Phone Number to track your order.</p>
        <a href="track_order.php" class="btn">Track Order</a>
        <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
    </div>
</main>
</body>
</html>