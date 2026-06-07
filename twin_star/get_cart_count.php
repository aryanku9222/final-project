<?php
require_once 'config.php';
echo json_encode(['count' => array_sum($_SESSION['cart'])]);
?>