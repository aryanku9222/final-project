<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = (int)$_POST['item_id'];
    unset($_SESSION['cart'][$item_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>