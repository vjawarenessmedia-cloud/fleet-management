<?php include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $part_name = $_POST['part_name'];
    $part_number = $_POST['part_number'];
    $quantity = $_POST['quantity_in_stock'];
    $min_qty = $_POST['minimum_quantity'];
    $price = $_POST['unit_price'];
    $location = $_POST['location'];

    $stmt = $pdo->prepare("INSERT INTO spare_parts (part_name, part_number, quantity_in_stock, minimum_quantity, unit_price, location) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$part_name, $part_number, $quantity, $min_qty, $price, $location]);

    $_SESSION['success'] = "Part added.";
    header('Location: spare_parts.php');
    exit;
}
?>

<h2>Add New Spare Part</h2>
<form method="post">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="part_name" class="form-label">Part Name</label>
            <input type="text" class="form-control" id="part_name" name="part_name" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="part_number" class="form-label">Part Number</label>
            <input type="text" class="form-control" id="part_number" name="part_number">
        </div>
        <div class="col-md-6 mb-3">
            <label for="quantity_in_stock" class="form-label">Initial Quantity</label>
            <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" value="0" min="0">
        </div>
        <div class="col-md-6 mb-3">
            <label for="minimum_quantity" class="form-label">Minimum Quantity (alert)</label>
            <input type="number" class="form-control" id="minimum_quantity" name="minimum_quantity" value="5" min="0">
        </div>
        <div class="col-md-6 mb-3">
            <label for="unit_price" class="form-label">Unit Price</label>
            <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price">
        </div>
        <div class="col-md-6 mb-3">
            <label for="location" class="form-label">Storage Location</label>
            <input type="text" class="form-control" id="location" name="location">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save Part</button>
    <a href="spare_parts.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include 'footer.php'; ?>