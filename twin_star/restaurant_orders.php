<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Restaurant Orders - Twin Star</title><link rel="stylesheet" href="style.css"></head>
<body>
<header><nav><a href="index.php">🍽️ Twin Star</a><a href="restaurant_orders.php">📋 All Orders</a></nav></header>
<main>
    <h1>Restaurant Order History</h1>
    <div class="orders-table">
        <table>
            <thead><tr><th>Order ID</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Delivery Status</th><th>Tracking ID</th><th>Date</th></tr></thead>
            <tbody>
            <?php
            $orders = $conn->query("SELECT o.*, c.name, c.phone FROM orders o JOIN customer c ON o.customer_id = c.customer_id ORDER BY o.order_date DESC");
            while ($order = $orders->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td><?php echo htmlspecialchars($order['name']); ?></td>
                <td><?php echo $order['phone']; ?></td>
                <td><?php echo CURRENCY; ?> <?php echo number_format($order['total_amount'],2); ?></td>
                <td><?php echo $order['order_status']; ?></td>
                <td><?php echo $order['delivery_status']; ?></td>
                <td><?php echo $order['tracking_id']; ?></td>
                <td><?php echo date('d M Y h:i A', strtotime($order['order_date'])); ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>