<?php
include 'config.php'; // DB connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM spare_parts WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php"); // redirect back
    exit;
} else {
    echo "Invalid ID";
}
?>