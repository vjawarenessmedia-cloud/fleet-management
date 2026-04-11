<?php include 'header.php';

$vehicles = $pdo->query("SELECT id, registration_number FROM vehicles WHERE status='active'")->fetchAll();
$parts = $pdo->query("SELECT id, part_name, part_number, quantity_in_stock FROM spare_parts ORDER BY part_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $service_date = $_POST['service_date'];
    $service_type = $_POST['service_type'];
    $cost = $_POST['cost'];
    $odometer = $_POST['odometer'];
    $next_service_date = !empty($_POST['next_service_date']) ? $_POST['next_service_date'] : null;
    $next_service_odometer = !empty($_POST['next_service_odometer']) ? $_POST['next_service_odometer'] : null;
    $notes = $_POST['notes'];

    $pdo->beginTransaction();
    try {
        // Insert service record
        $stmt = $pdo->prepare("INSERT INTO service_records (vehicle_id, service_date, service_type, cost, odometer, next_service_date, next_service_odometer, notes) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$vehicle_id, $service_date, $service_type, $cost, $odometer, $next_service_date, $next_service_odometer, $notes]);
        $service_id = $pdo->lastInsertId();

        // Insert parts used (if any)
        if (isset($_POST['parts']) && is_array($_POST['parts'])) {
            foreach ($_POST['parts'] as $part_id => $qty) {
                if ($qty > 0) {
                    // Check stock
                    $check = $pdo->prepare("SELECT quantity_in_stock FROM spare_parts WHERE id = ?");
                    $check->execute([$part_id]);
                    $stock = $check->fetchColumn();
                    if ($stock < $qty) {
                        throw new Exception("Insufficient stock for part ID $part_id");
                    }
                    // Insert usage
                    $stmt2 = $pdo->prepare("INSERT INTO service_parts_used (service_record_id, part_id, quantity_used) VALUES (?,?,?)");
                    $stmt2->execute([$service_id, $part_id, $qty]);
                    // Reduce stock
                    $pdo->prepare("UPDATE spare_parts SET quantity_in_stock = quantity_in_stock - ? WHERE id = ?")->execute([$qty, $part_id]);
                }
            }
        }

        // Create a reminder for next service if date or odometer is given
        if ($next_service_date || $next_service_odometer) {
            $reminder_message = "Next service due";
            if ($next_service_date) $reminder_message .= " on $next_service_date";
            if ($next_service_odometer) $reminder_message .= " or at $next_service_odometer km";
            $reminder_date = $next_service_date ?? date('Y-m-d', strtotime('+30 days')); // fallback

            $stmt3 = $pdo->prepare("INSERT INTO reminders (vehicle_id, reminder_date, reminder_type, message) VALUES (?,?,?,?)");
            $stmt3->execute([$vehicle_id, $reminder_date, 'Service', $reminder_message]);
        }

        $pdo->commit();
        $_SESSION['success'] = "Service record added successfully.";
        header('Location: service_records.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>

<h2>Add Service Record</h2>
<?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="post">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="vehicle_id" class="form-label">Vehicle</label>
            <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                <option value="">Select Vehicle</option>
                <?php foreach ($vehicles as $v): ?>
                    <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['registration_number']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="service_date" class="form-label">Service Date</label>
            <input type="date" class="form-control" id="service_date" name="service_date" required value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="service_type" class="form-label">Service Type</label>
            <input type="text" class="form-control" id="service_type" name="service_type" placeholder="e.g., Oil Change, General" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="cost" class="form-label">Cost (₹)</label>
            <input type="number" step="0.01" class="form-control" id="cost" name="cost">
        </div>
        <div class="col-md-6 mb-3">
            <label for="odometer" class="form-label">Odometer Reading (km)</label>
            <input type="number" class="form-control" id="odometer" name="odometer" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="next_service_date" class="form-label">Next Service Date (optional)</label>
            <input type="date" class="form-control" id="next_service_date" name="next_service_date">
        </div>
        <div class="col-md-6 mb-3">
            <label for="next_service_odometer" class="form-label">Next Service Odometer (optional)</label>
            <input type="number" class="form-control" id="next_service_odometer" name="next_service_odometer">
        </div>
        <div class="col-12 mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
        </div>
    </div>

    <h5 class="mt-3">Parts Used (optional)</h5>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Part Name</th>
                <th>Part Number</th>
                <th>Stock</th>
                <th>Quantity Used</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parts as $part): ?>
                <tr>
                    <td><?= htmlspecialchars($part['part_name']) ?></td>
                    <td><?= htmlspecialchars($part['part_number']) ?></td>
                    <td><?= $part['quantity_in_stock'] ?></td>
                    <td>
                        <input type="number" name="parts[<?= $part['id'] ?>]" class="form-control form-control-sm" min="0" max="<?= $part['quantity_in_stock'] ?>" value="0">
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Save Service</button>
    <a href="service_records.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include 'footer.php'; ?>