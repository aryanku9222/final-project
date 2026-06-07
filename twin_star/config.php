<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'twin_star_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Set currency symbol
define('CURRENCY', '₹');
?>