<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Fuel Logs</h2>
    <a href="add_fuel.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Fuel Entry</a>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Date</th>
            <th>Vehicle</th>
            <th>Quantity (L)</th>
            <th>Cost ($)</th>
            <th>Odometer</th>
            <th>Full Refill</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT f.*, v.registration_number FROM fuel_logs f JOIN vehicles v ON f.vehicle_id = v.id ORDER BY f.date DESC");
        while ($row = $stmt->fetch()) {
            echo "<tr>
                <td>{$row['date']}</td>
                <td>{$row['registration_number']}</td>
                <td>{$row['fuel_quantity']}</td>
                <td>\${$row['cost']}</td>
                <td>{$row['odometer']}</td>
                <td>" . ($row['full_refill'] ? 'Yes' : 'No') . "</td>
                <td>
                    <a href='edit_fuel.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i></a>
                    <a href='delete_fuel.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='bi bi-trash'></i></a>
                </td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>