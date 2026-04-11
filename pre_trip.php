<?php include 'header.php';

$vehicles = $pdo->query("SELECT id, registration_number FROM vehicles WHERE status='active'")->fetchAll();
$drivers = $pdo->query("SELECT id, name FROM drivers WHERE status='active'")->fetchAll();

// Predefined checklist items
$checklistItems = [
    'tires' => 'Tires (pressure/condition)',
    'lights' => 'Headlights/Taillights/Indicators',
    'brakes' => 'Brakes',
    'fluids' => 'Fluids (oil, coolant, washer)',
    'battery' => 'Battery',
    'spare_tire' => 'Spare Tire & Tools',
    'fuel' => 'Fuel Level',
    'mirrors' => 'Mirrors',
    'horn' => 'Horn',
    'seatbelts' => 'Seatbelts'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $driver_id = !empty($_POST['driver_id']) ? $_POST['driver_id'] : null;
    $notes = $_POST['notes'];

    // Build JSON of checklist items with status
    $items = [];
    foreach ($checklistItems as $key => $label) {
        $items[$key] = $_POST['check_' . $key] ?? 'fail'; // default fail if not checked
    }
    $itemsJson = json_encode($items);

    $stmt = $pdo->prepare("INSERT INTO pre_trip_inspections (vehicle_id, driver_id, checklist_items, notes) VALUES (?,?,?,?)");
    $stmt->execute([$vehicle_id, $driver_id, $itemsJson, $notes]);

    $_SESSION['success'] = "Pre-trip inspection recorded.";
    header('Location: pre_trip_list.php');
    exit;
}
?>

<h2>Pre‑Trip Inspection Checklist</h2>
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
            <label for="driver_id" class="form-label">Driver (optional)</label>
            <select class="form-select" id="driver_id" name="driver_id">
                <option value="">-- None --</option>
                <?php foreach ($drivers as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <h5 class="mt-3">Checklist</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($checklistItems as $key => $label): ?>
                <tr>
                    <td><?= $label ?></td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="check_<?= $key ?>" id="<?= $key ?>_ok" value="ok" checked>
                            <label class="form-check-label" for="<?= $key ?>_ok">OK</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="check_<?= $key ?>" id="<?= $key ?>_fail" value="fail">
                            <label class="form-check-label" for="<?= $key ?>_fail">Fail</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="check_<?= $key ?>" id="<?= $key ?>_na" value="na">
                            <label class="form-check-label" for="<?= $key ?>_na">N/A</label>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mb-3">
        <label for="notes" class="form-label">Additional Notes</label>
        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit Inspection</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include 'footer.php'; ?>