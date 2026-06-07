<?php
// install.php - Run this file once to import database.sql
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'twin_star_db';

// Connect without database first
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Drop database if exists (optional, remove if you want to keep old data)
$conn->query("DROP DATABASE IF EXISTS $dbname");

// Create database
if (!$conn->query("CREATE DATABASE $dbname")) die("Error creating DB: " . $conn->error);
$conn->select_db($dbname);

// Read SQL file
$sqlFile = file_get_contents('database.sql');
if ($sqlFile === false) die("Could not read database.sql file");

// Split SQL by semicolons (very basic – works for our file)
$queries = explode(';', $sqlFile);
$success = true;
foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if (!$conn->query($query)) {
            echo "Error executing: " . $query . "<br>" . $conn->error . "<br>";
            $success = false;
        }
    }
}

if ($success) {
    echo "✅ Database imported successfully! You can now delete this file.<br>";
    echo "<a href='index.php'>Go to Menu</a>";
} else {
    echo "⚠️ Some errors occurred, but tables may still be created.";
}
$conn->close();
?>