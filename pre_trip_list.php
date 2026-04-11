<?php include 'header.php'; ?>

<h2>Past Pre‑Trip Inspections</h2>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Date</th>
            <th>Vehicle</th>
            <th>Driver</th>
            <th>Items (summary)</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT p.*, v.registration_number, d.name as driver_name FROM pre_trip_inspections p LEFT JOIN vehicles v ON p.vehicle_id = v.id LEFT JOIN drivers d ON p.driver_id = d.id ORDER BY p.inspection_date DESC");
        while ($row = $stmt->fetch()) {
            $items = json_decode($row['checklist_items'], true);
            $failCount = is_array($items) ? count(array_filter($items, fn($s) => $s == 'fail')) : 0;
            echo "<tr>
                <td>{$row['inspection_date']}</td>
                <td>{$row['registration_number']}</td>
                <td>" . ($row['driver_name'] ?? 'N/A') . "</td>
                <td>" . ($failCount ? "<span class='badge bg-danger'>$failCount fails</span>" : "<span class='badge bg-success'>All OK</span>") . "</td>
                <td>{$row['notes']}</td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>