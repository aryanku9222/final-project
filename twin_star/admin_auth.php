<?php
session_start();
require_once 'config.php';
$email = $_POST['email'];
$pass = md5($_POST['password']);
$stmt = $conn->prepare("SELECT * FROM admin WHERE email=? AND password=?");
$stmt->bind_param("ss", $email, $pass);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $_SESSION['admin'] = $email;
    header('Location: admin_panel.php');
} else {
    header('Location: admin_login.php?error=1');
}
?>