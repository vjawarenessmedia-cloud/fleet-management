<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// FETCH DATA
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM service_records WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();

    if (!$service) {
        echo "Record not found";
        exit;
    }
} else {
    echo "Invalid request";
    exit;
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $vehicle_id = $_POST['vehicle_id'];
    $service_date = $_POST['service_date'];
    $service_type = $_POST['service_type'];
    $cost = $_POST['cost'];
    $odometer = $_POST['odometer'];
    $next_date = $_POST['next_service_date'];
    $next_odometer = $_POST['next_service_odometer'];

    $stmt = $pdo->prepare("UPDATE service_records SET 
        vehicle_id = ?, 
        service_date = ?, 
        service_type = ?, 
        cost = ?, 
        odometer = ?, 
        next_service_date = ?, 
        next_service_odometer = ?
        WHERE id = ?");

    $stmt->execute([
        $vehicle_id,
        $service_date,
        $service_type,
        $cost,
        $odometer,
        $next_date,
        $next_odometer,
        $id
    ]);

    header("Location: service_records.php"); // 🔴 change if needed
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Edit Service Record</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $service['id'] ?>">

    <div class="mb-3">
        <label>Vehicle ID</label>
        <input type="number" name="vehicle_id" class="form-control" value="<?= $service['vehicle_id'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Service Date</label>
        <input type="date" name="service_date" class="form-control" value="<?= $service['service_date'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Service Type</label>
        <input type="text" name="service_type" class="form-control" value="<?= $service['service_type'] ?>">
    </div>

    <div class="mb-3">
        <label>Cost</label>
        <input type="text" name="cost" class="form-control" value="<?= $service['cost'] ?>">
    </div>

    <div class="mb-3">
        <label>Odometer</label>
        <input type="number" name="odometer" class="form-control" value="<?= $service['odometer'] ?>">
    </div>

    <div class="mb-3">
        <label>Next Service Date</label>
        <input type="date" name="next_service_date" class="form-control" value="<?= $service['next_service_date'] ?>">
    </div>

    <div class="mb-3">
        <label>Next Service Odometer</label>
        <input type="number" name="next_service_odometer" class="form-control" value="<?= $service['next_service_odometer'] ?>">
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="service_records.php" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>