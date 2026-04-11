<?php include 'header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->execute([$id]);
$vehicle = $stmt->fetch();
if (!$vehicle) {
    $_SESSION['error'] = "Vehicle not found.";
    header('Location: vehicles.php');
    exit;
}

$drivers = $pdo->query("SELECT id, name FROM drivers WHERE status='active'")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg = $_POST['registration_number'];
    $model = $_POST['model'];
    $make = $_POST['make'];
    $year = $_POST['year'];
    $fuel = $_POST['fuel_type'];
    $odometer = $_POST['odometer'];
    $status = $_POST['status'];
    $driver_id = !empty($_POST['driver_id']) ? $_POST['driver_id'] : null;

    $stmt = $pdo->prepare("UPDATE vehicles SET registration_number=?, model=?, make=?, year=?, fuel_type=?, odometer=?, status=?, driver_id=? WHERE id=?");
    $stmt->execute([$reg, $model, $make, $year, $fuel, $odometer, $status, $driver_id, $id]);

    $_SESSION['success'] = "Vehicle updated successfully.";
    header('Location: vehicles.php');
    exit;
}
?>

<h2>Edit Vehicle</h2>
<form method="post">
    <!-- same form as add_vehicle.php with values filled from $vehicle -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="registration_number" class="form-label">Registration Number</label>
            <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?= htmlspecialchars($vehicle['registration_number']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control" id="model" name="model" value="<?= htmlspecialchars($vehicle['model']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="make" class="form-label">Make</label>
            <input type="text" class="form-control" id="make" name="make" value="<?= htmlspecialchars($vehicle['make']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" class="form-control" id="year" name="year" value="<?= htmlspecialchars($vehicle['year']) ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="fuel_type" class="form-label">Fuel Type</label>
            <select class="form-select" id="fuel_type" name="fuel_type">
                <option <?= $vehicle['fuel_type']=='Petrol'?'selected':'' ?>>Petrol</option>
                <option <?= $vehicle['fuel_type']=='Diesel'?'selected':'' ?>>Diesel</option>
                <option <?= $vehicle['fuel_type']=='Electric'?'selected':'' ?>>Electric</option>
                <option <?= $vehicle['fuel_type']=='Hybrid'?'selected':'' ?>>Hybrid</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="odometer" class="form-label">Current Odometer (km)</label>
            <input type="number" class="form-control" id="odometer" name="odometer" value="<?= htmlspecialchars($vehicle['odometer']) ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="active" <?= $vehicle['status']=='active'?'selected':'' ?>>Active</option>
                <option value="inactive" <?= $vehicle['status']=='inactive'?'selected':'' ?>>Inactive</option>
                <option value="maintenance" <?= $vehicle['status']=='maintenance'?'selected':'' ?>>Maintenance</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="driver_id" class="form-label">Assign Driver (optional)</label>
            <select class="form-select" id="driver_id" name="driver_id">
                <option value="">-- None --</option>
                <?php foreach ($drivers as $driver): ?>
                    <option value="<?= $driver['id'] ?>" <?= $vehicle['driver_id'] == $driver['id'] ? 'selected' : '' ?>><?= htmlspecialchars($driver['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Update Vehicle</button>
    <a href="vehicles.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include 'footer.php'; ?>