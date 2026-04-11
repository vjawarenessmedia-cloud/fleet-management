<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Service Records</h2>
    <a href="add_service.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Service</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Vehicle</th>
            <th>Date</th>
            <th>Type</th>
            <th>Cost</th>
            <th>Odometer</th>
            <th>Next Service</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT s.*, v.registration_number FROM service_records s JOIN vehicles v ON s.vehicle_id = v.id ORDER BY s.service_date DESC");
        while ($row = $stmt->fetch()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['registration_number']}</td>
                <td>{$row['service_date']}</td>
                <td>{$row['service_type']}</td>
                <td>\${$row['cost']}</td>
                <td>{$row['odometer']}</td>
                <td>" . ($row['next_service_date'] ?? 'N/A') . " / " . ($row['next_service_odometer'] ?? 'N/A') . "</td>
                <td>
                    <a href='edit_service.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i></a>
                    <a href='delete_service.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='bi bi-trash'></i></a>
                </td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>