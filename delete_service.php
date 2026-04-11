<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM service_records WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: service_records.php"); // 🔴 change if file name different
    exit;
} else {
    echo "Invalid request";
}
?>