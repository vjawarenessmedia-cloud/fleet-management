<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete query
    $stmt = $pdo->prepare("DELETE FROM drivers WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect back to drivers page
    header("Location: drivers.php"); // 🔴 change if your file name is different
    exit;
} else {
    echo "Invalid request";
}
?>