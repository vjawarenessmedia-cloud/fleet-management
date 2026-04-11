<?php
require_once 'config.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("UPDATE reminders SET status='completed' WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "Reminder marked as completed.";
header('Location: reminders.php');
exit;
?>