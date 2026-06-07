<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'], $_POST['quantity'])) {
    $item_id = (int)$_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$item_id]);
    } else {
        $_SESSION['cart'][$item_id] = $quantity;
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>