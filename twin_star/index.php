<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twin Star - Menu</title>
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
    <h1>Welcome to Twin Star</h1>
    <h2>Our Specialties</h2>
    <div class="menu-grid">
        <?php
        $result = $conn->query("SELECT * FROM menu WHERE availability_status = 'Available' ORDER BY category, food_name");
        while ($item = $result->fetch_assoc()):
            // Create image filename from food name (lowercase, replace spaces with _)
            $image_name = strtolower(str_replace(' ', '_', $item['food_name'])) . '.jpg';
            $image_path = "images/" . $image_name;
        ?>
        <div class="menu-item">
            <div class="image-container">
                <img src="<?php echo $image_path; ?>" 
                     alt="<?php echo htmlspecialchars($item['food_name']); ?>"
                     onerror="this.src='https://via.placeholder.com/300x200?text=' + encodeURIComponent('<?php echo $item['food_name']; ?>')">
            </div>
            <div class="item-info">
                <h3><?php echo htmlspecialchars($item['food_name']); ?></h3>
                <p class="category"><?php echo $item['category']; ?></p>
                <p><?php echo htmlspecialchars($item['description']); ?></p>
                <div class="price">₹ <?php echo number_format($item['price'], 2); ?></div>
                <button class="btn add-to-cart" data-id="<?php echo $item['menu_id']; ?>">Add to Cart</button>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>