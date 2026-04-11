<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// CHECK ID
if (!isset($_GET['id'])) {
    echo "Invalid request";
    exit;
}

$id = $_GET['id'];

// FETCH DATA
$stmt = $pdo->prepare("SELECT * FROM spare_parts WHERE id = ?");
$stmt->execute([$id]);
$part = $stmt->fetch();

if (!$part) {
    echo "Record not found";
    exit;
}

// UPDATE DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $stmt = $pdo->prepare("UPDATE spare_parts SET 
        part_name = ?, 
        part_number = ?, 
        quantity_in_stock = ?, 
        minimum_quantity = ?, 
        unit_price = ?, 
        location = ?
        WHERE id = ?");

    $stmt->execute([
        $_POST['part_name'],
        $_POST['part_number'],
        $_POST['quantity'],
        $_POST['min_qty'],
        $_POST['price'],
        $_POST['location'],
        $_POST['id']
    ]);

    header("Location: index.php"); // 🔴 change if needed
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Spare Part</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Edit Spare Part</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $part['id'] ?>">

    <div class="mb-3">
        <label>Part Name</label>
        <input type="text" name="part_name" class="form-control" value="<?= $part['part_name'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Part Number</label>
        <input type="text" name="part_number" class="form-control" value="<?= $part['part_number'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" value="<?= $part['quantity_in_stock'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Minimum Quantity</label>
        <input type="number" name="min_qty" class="form-control" value="<?= $part['minimum_quantity'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Unit Price</label>
        <input type="text" name="price" class="form-control" value="<?= $part['unit_price'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Location</label>
        <input type="text" name="location" class="form-control" value="<?= $part['location'] ?>">
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>