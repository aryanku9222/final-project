<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

$name = trim($_POST['customer_name']);
$phone = trim($_POST['customer_phone']);
$email = trim($_POST['customer_email']);
$address = trim($_POST['customer_address']);
$payment_method = $_POST['payment_method'];

if (empty($name) || empty($phone) || empty($address) || empty($_SESSION['cart'])) {
    header('Location: checkout.php?error=1');
    exit;
}

// Find or create customer
$stmt = $conn->prepare("SELECT customer_id FROM customer WHERE phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $customer_id = $row['customer_id'];
    // update name/address if changed
    $stmt2 = $conn->prepare("UPDATE customer SET name=?, email=?, address=? WHERE customer_id=?");
    $stmt2->bind_param("sssi", $name, $email, $address, $customer_id);
    $stmt2->execute();
} else {
    $stmt2 = $conn->prepare("INSERT INTO customer (name, email, phone, address) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("ssss", $name, $email, $phone, $address);
    $stmt2->execute();
    $customer_id = $conn->insert_id;
}

// Calculate total and get cart items
$total = 0;
$cart_items = [];
foreach ($_SESSION['cart'] as $item_id => $quantity) {
    $stmt = $conn->prepare("SELECT menu_id, food_name, price FROM menu WHERE menu_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    if ($item) {
        $item_total = $item['price'] * $quantity;
        $total += $item_total;
        $cart_items[] = ['id' => $item['menu_id'], 'name' => $item['food_name'], 'price' => $item['price'], 'quantity' => $quantity];
    }
}

// Generate unique tracking ID
$tracking_id = 'TWIN' . strtoupper(uniqid());

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (customer_id, total_amount, tracking_id, delivery_status) VALUES (?, ?, ?, 'Order Placed')");
$stmt->bind_param("ids", $customer_id, $total, $tracking_id);
$stmt->execute();
$order_id = $conn->insert_id;

// Insert order details
foreach ($cart_items as $item) {
    $stmt = $conn->prepare("INSERT INTO order_details (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Insert payment record
$stmt = $conn->prepare("INSERT INTO payment (order_id, method, amount) VALUES (?, ?, ?)");
$stmt->bind_param("isd", $order_id, $payment_method, $total);
$stmt->execute();

// Clear cart
$_SESSION['cart'] = [];

header("Location: order_success.php?order_id=$order_id&tracking=$tracking_id");
exit;
?>