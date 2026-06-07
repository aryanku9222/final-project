<?php 
require_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - Twin Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="index.php">🍽️ Twin Star</a>
        <a href="cart.php">🛒 Cart (<span id="cart-count">0</span>)</a>
        <a href="track_order.php">📦 Track Order</a>
        <a href="restaurant_orders.php">📋 Orders</a>
    </nav>
</header>
<main>
    <h1>Track Your Order</h1>
    
    <?php
    // Check if form was submitted
    if (isset($_GET['tracking_id']) && isset($_GET['phone'])) {
        $tracking_id = trim($_GET['tracking_id']);
        $phone = trim($_GET['phone']);
        
        // Debug: Show what we're searching for
        echo "<!-- Searching for Tracking ID: " . htmlspecialchars($tracking_id) . " with Phone: " . htmlspecialchars($phone) . " -->";
        
        // Query to find order
        $sql = "SELECT o.order_id, o.tracking_id, o.order_status, o.delivery_status, 
                       o.order_date, o.total_amount, c.name, c.phone, c.address
                FROM orders o 
                JOIN customer c ON o.customer_id = c.customer_id 
                WHERE o.tracking_id = ? AND c.phone = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $tracking_id, $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($order = $result->fetch_assoc()) {
            // Order found - display tracking info
            ?>
            <div class="tracking-result">
                <h2>✅ Order Found!</h2>
                
                <div class="order-details">
                    <p><strong>📋 Order ID:</strong> #<?php echo $order['order_id']; ?></p>
                    <p><strong>🔑 Tracking ID:</strong> <?php echo htmlspecialchars($order['tracking_id']); ?></p>
                    <p><strong>👤 Customer Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>📞 Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                    <p><strong>📍 Delivery Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                    <p><strong>📅 Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></p>
                    <p><strong>💰 Total Amount:</strong> ₹ <?php echo number_format($order['total_amount'], 2); ?></p>
                    <p><strong>📊 Order Status:</strong> 
                        <span class="status-badge status-<?php echo strtolower($order['order_status']); ?>">
                            <?php echo $order['order_status']; ?>
                        </span>
                    </p>
                    <p><strong>🚚 Delivery Status:</strong> 
                        <span class="status-badge">
                            <?php echo $order['delivery_status']; ?>
                        </span>
                    </p>
                </div>
                
                <!-- Progress Bar for Delivery -->
                <h3>Delivery Progress</h3>
                <div class="progress-container">
                    <div class="progress-step <?php echo ($order['order_status'] == 'Pending' || $order['order_status'] == 'Preparing' || $order['order_status'] == 'Dispatched' || $order['order_status'] == 'Delivered') ? 'active' : ''; ?>">
                        📝 Order Placed
                    </div>
                    <div class="progress-step <?php echo ($order['order_status'] == 'Preparing' || $order['order_status'] == 'Dispatched' || $order['order_status'] == 'Delivered') ? 'active' : ''; ?>">
                        🔪 Preparing
                    </div>
                    <div class="progress-step <?php echo ($order['order_status'] == 'Dispatched' || $order['order_status'] == 'Delivered') ? 'active' : ''; ?>">
                        🚚 Dispatched
                    </div>
                    <div class="progress-step <?php echo ($order['order_status'] == 'Delivered') ? 'active' : ''; ?>">
                        🏠 Delivered
                    </div>
                </div>
            </div>
            <?php
        } else {
            // Order not found
            ?>
            <div class="error-message">
                <h3>❌ Order Not Found</h3>
                <p>No order found with Tracking ID: <strong><?php echo htmlspecialchars($tracking_id); ?></strong> and Phone: <strong><?php echo htmlspecialchars($phone); ?></strong></p>
                <p>Please check your Tracking ID and Phone Number and try again.</p>
                <p><small>Tip: Copy and paste the Tracking ID exactly as shown on your order confirmation page.</small></p>
            </div>
            <?php
        }
        $stmt->close();
    }
    ?>
    
    <!-- Tracking Form -->
    <form method="GET" class="tracking-form">
        <div class="form-group">
            <label>🔑 Tracking ID *</label>
            <input type="text" name="tracking_id" placeholder="Example: TWIN5f8a9c3b2d1e" required 
                   value="<?php echo isset($_GET['tracking_id']) ? htmlspecialchars($_GET['tracking_id']) : ''; ?>">
            <small>Enter the Tracking ID from your order confirmation</small>
        </div>
        
        <div class="form-group">
            <label>📞 Phone Number *</label>
            <input type="tel" name="phone" placeholder="Enter your 10-digit phone number" required 
                   value="<?php echo isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : ''; ?>">
            <small>Use the same phone number you provided during checkout</small>
        </div>
        
        <button type="submit" class="btn">🔍 Track My Order</button>
    </form>
    
    <!-- Help Section -->
    <div class="help-section">
        <h3>💡 Don't have a Tracking ID?</h3>
        <p>If you've placed an order but don't have your Tracking ID:</p>
        <ol>
            <li>Check your order confirmation page</li>
            <li>Contact the restaurant with your phone number</li>
            <li>Admin can look it up in <a href="restaurant_orders.php">Restaurant Orders</a></li>
        </ol>
    </div>
</main>

<style>
.tracking-form {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    max-width: 500px;
    margin: 2rem auto;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.tracking-result {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.order-details p {
    margin: 10px 0;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 8px;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: bold;
}

.status-pending { background: #ffc107; color: #333; }
.status-preparing { background: #17a2b8; color: white; }
.status-dispatched { background: #007bff; color: white; }
.status-delivered { background: #28a745; color: white; }

.progress-container {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    flex-wrap: wrap;
}

.progress-step {
    flex: 1;
    text-align: center;
    padding: 10px;
    background: #e9ecef;
    margin: 0 5px;
    border-radius: 8px;
    font-size: 14px;
}

.progress-step.active {
    background: #28a745;
    color: white;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    text-align: center;
}

.help-section {
    background: #d1ecf1;
    color: #0c5460;
    padding: 1.5rem;
    border-radius: 15px;
    margin-top: 2rem;
}

small {
    color: #6c757d;
    font-size: 12px;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
}

.btn {
    width: 100%;
    padding: 12px;
    font-size: 16px;
}
</style>

<script src="script.js"></script>
</body>
</html>