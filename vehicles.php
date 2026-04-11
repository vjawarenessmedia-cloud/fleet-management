<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Vehicles</h2>
    <a href="add_vehicle.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Vehicle</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Reg Number</th>
            <th>Model</th>
            <th>Make</th>
            <th>Year</th>
            <th>Fuel Type</th>
            <th>Odometer</th>
            <th>Status</th>
            <th>Driver</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT v.*, d.name as driver_name FROM vehicles v LEFT JOIN drivers d ON v.driver_id = d.id ORDER BY v.id DESC");
        while ($row = $stmt->fetch()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['registration_number']}</td>
                <td>{$row['model']}</td>
                <td>{$row['make']}</td>
                <td>{$row['year']}</td>
                <td>{$row['fuel_type']}</td>
                <td>{$row['odometer']}</td>
                <td><span class='badge bg-" . ($row['status']=='active'?'success':'secondary') . "'>{$row['status']}</span></td>
                <td>" . ($row['driver_name'] ?? 'Unassigned') . "</td>
                <td>
                    <a href='edit_vehicle.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i></a>
                    <a href='delete_vehicle.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='bi bi-trash'></i></a>
                </td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>