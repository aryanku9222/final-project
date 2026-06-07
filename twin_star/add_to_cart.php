<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = (int)$_POST['item_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($quantity > 0) {
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id] += $quantity;
        } else {
            $_SESSION['cart'][$item_id] = $quantity;
        }
    }
    echo json_encode(['success' => true, 'cart_count' => array_sum($_SESSION['cart'])]);
} else {
    echo json_encode(['success' => false]);
}
?>