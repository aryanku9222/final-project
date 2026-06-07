<?php
session_start();
if (!isset($_SESSION['admin'])) { header('Location: admin_login.php'); exit; }
require_once 'config.php';

// Handle add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO menu (food_name, category, price, description) VALUES (?,?,?,?)");
        $stmt->bind_param("ssds", $_POST['food_name'], $_POST['category'], $_POST['price'], $_POST['description']);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM menu WHERE menu_id=?");
        $stmt->bind_param("i", $_POST['menu_id']);
        $stmt->execute();
    }
    header("Location: admin_panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Panel</title><link rel="stylesheet" href="style.css"></head>
<body>
<h1>Manage Menu</h1>
<form method="POST" style="margin-bottom:20px">
    <input type="text" name="food_name" placeholder="Food Name" required>
    <input type="text" name="category" placeholder="Category">
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit" name="add">Add Item</button>
</form>
<table border="1" cellpadding="8">
    <tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Action</th></tr>
    <?php
    $items = $conn->query("SELECT * FROM menu");
    while($row = $items->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $row['menu_id']; ?></td>
        <td><?php echo htmlspecialchars($row['food_name']); ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo CURRENCY; ?> <?php echo $row['price']; ?></td>
        <td>
            <form method="POST" style="display:inline">
                <input type="hidden" name="menu_id" value="<?php echo $row['menu_id']; ?>">
                <button type="submit" name="delete" onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<a href="index.php">Back to Site</a>
</body>
</html>