<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Admin Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<main>
    <form method="POST" action="admin_auth.php" class="checkout-form">
        <h2>Admin Login</h2>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <button type="submit">Login</button>
    </form>
</main>
</body>
</html>