<?php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $license_number = $_POST['license_number'];
    $phone = $_POST['phone'];
    $hired_date = $_POST['hired_date'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO drivers (name, license_number, phone, hired_date, status) VALUES (?, ?, ?, ?, ?)");

    if ($stmt->execute([$name, $license_number, $phone, $hired_date, $status])) {
        header('Location: drivers.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Failed to add driver.</div>";
    }
}
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Add Driver</h3>
        </div>

        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Driver Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">License Number</label>
                    <input type="text" name="license_number" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hired Date</label>
                    <input type="date" name="hired_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Save Driver
                </button>
                <a href="drivers.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>



