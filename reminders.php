<?php include 'header.php'; ?>

<h2>Pending Reminders</h2>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Due Date</th>
            <th>Vehicle</th>
            <th>Type</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT r.*, v.registration_number FROM reminders r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.status='pending' ORDER BY r.reminder_date");
        while ($row = $stmt->fetch()) {
            $class = $row['reminder_date'] < date('Y-m-d') ? 'table-danger' : '';
            echo "<tr class='$class'>
                <td>{$row['reminder_date']}</td>
                <td>{$row['registration_number']}</td>
                <td>{$row['reminder_type']}</td>
                <td>{$row['message']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='mark_reminder.php?id={$row['id']}' class='btn btn-sm btn-success' onclick='return confirm(\"Mark as completed?\")'>Mark Done</a>
                </td>
            </tr>";
        }
        if ($stmt->rowCount() == 0) {
            echo "<tr><td colspan='6' class='text-center'>No pending reminders.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>