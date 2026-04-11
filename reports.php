<?php include 'header.php'; ?>

<h2>Vehicle Expense Report</h2>
<form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
        <label for="vehicle_id" class="form-label">Select Vehicle</label>
        <select name="vehicle_id" id="vehicle_id" class="form-select">
            <option value="">-- All Vehicles --</option>
            <?php
            $vehicles = $pdo->query("SELECT id, registration_number FROM vehicles ORDER BY registration_number");
            while ($v = $vehicles->fetch()) {
                $selected = ($_GET['vehicle_id'] ?? '') == $v['id'] ? 'selected' : '';
                echo "<option value='{$v['id']}' $selected>{$v['registration_number']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="from" class="form-label">From Date</label>
        <input type="date" class="form-control" name="from" id="from" value="<?= $_GET['from'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <label for="to" class="form-label">To Date</label>
        <input type="date" class="form-control" name="to" id="to" value="<?= $_GET['to'] ?? '' ?>">
    </div>
    <div class="col-md-2 align-self-end">
        <button type="submit" class="btn btn-primary">Generate</button>
    </div>
</form>

<?php
$vehicleFilter = $_GET['vehicle_id'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

// Build query conditions
$conditions = [];
$params = [];
if (!empty($vehicleFilter)) {
    $conditions[] = "vehicle_id = ?";
    $params[] = $vehicleFilter;
}
if (!empty($from)) {
    $conditions[] = "service_date >= ?";
    $params[] = $from;
}
if (!empty($to)) {
    $conditions[] = "service_date <= ?";
    $params[] = $to;
}
$where = '';
if (!empty($conditions)) {
    $where = "WHERE " . implode(" AND ", $conditions);
}

// Service costs
$stmt = $pdo->prepare("SELECT SUM(cost) as total_service FROM service_records $where");
$stmt->execute($params);
$totalService = $stmt->fetchColumn();

// Fuel costs (using same date range but fuel_logs table)
$fuelConditions = $conditions; // replace service_date with date
$fuelWhere = '';
if (!empty($fuelConditions)) {
    // need to adjust column name: fuel_logs uses 'date'
    $fuelConditions = str_replace('service_date', 'date', $fuelConditions);
    $fuelWhere = "WHERE " . implode(" AND ", $fuelConditions);
}
$stmt2 = $pdo->prepare("SELECT SUM(cost) as total_fuel FROM fuel_logs $fuelWhere");
$stmt2->execute($params);
$totalFuel = $stmt2->fetchColumn();

// Parts used cost (through service_parts_used, but we need to join service_records for date/vehicle)
// For simplicity, we'll just show service and fuel totals.
?>

<div class="row">
    <div class="col-md-6">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Service Cost</h5>
                <h3>₹<?= number_format($totalService ?: 0, 2) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Fuel Cost</h5>
                <h3>₹<?= number_format($totalFuel ?: 0, 2) ?></h3>
            </div>
        </div>
    </div>
</div>

<h4 class="mt-4">Service Records</h4>
<table class="table table-sm">
    <thead>
        <tr>
            <th>Date</th>
            <th>Vehicle</th>
            <th>Type</th>
            <th>Cost</th>
            <th>Odometer</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $serviceQuery = "SELECT s.*, v.registration_number FROM service_records s JOIN vehicles v ON s.vehicle_id = v.id $where ORDER BY s.service_date DESC";
        $stmt3 = $pdo->prepare($serviceQuery);
        $stmt3->execute($params);
        while ($row = $stmt3->fetch()) {
            echo "<tr>
                <td>{$row['service_date']}</td>
                <td>{$row['registration_number']}</td>
                <td>{$row['service_type']}</td>
                <td>\${$row['cost']}</td>
                <td>{$row['odometer']}</td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>