<?php
require_once 'config.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "Vehicle deleted.";
header('Location: vehicles.php');
exit;
?>