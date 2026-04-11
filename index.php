<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Vehicles</h5>
                <?php
                $stmt = $pdo->query("SELECT COUNT(*) FROM vehicles");
                $count = $stmt->fetchColumn();
                ?>
                <h2><?= $count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Active Drivers</h5>
                <?php
                $stmt = $pdo->query("SELECT COUNT(*) FROM drivers WHERE status='active'");
                $count = $stmt->fetchColumn();
                ?>
                <h2><?= $count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Pending Reminders</h5>
                <?php
                $stmt = $pdo->query("SELECT COUNT(*) FROM reminders WHERE status='pending' AND reminder_date <= CURDATE()");
                $count = $stmt->fetchColumn();
                ?>
                <h2><?= $count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Low Stock Parts</h5>
                <?php
                $stmt = $pdo->query("SELECT COUNT(*) FROM spare_parts WHERE quantity_in_stock <= minimum_quantity");
                $count = $stmt->fetchColumn();
                ?>
                <h2><?= $count ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Service Records</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT s.*, v.registration_number FROM service_records s JOIN vehicles v ON s.vehicle_id = v.id ORDER BY s.service_date DESC LIMIT 5");
                        while ($row = $stmt->fetch()) {
                            echo "<tr>
                                <td>{$row['registration_number']}</td>
                                <td>{$row['service_date']}</td>
                                <td>{$row['service_type']}</td>
                                <td>\${$row['cost']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Upcoming Reminders</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Due Date</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT r.*, v.registration_number FROM reminders r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.status='pending' AND r.reminder_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY r.reminder_date LIMIT 5");
                        while ($row = $stmt->fetch()) {
                            $class = $row['reminder_date'] < date('Y-m-d') ? 'table-danger' : '';
                            echo "<tr class='$class'>
                                <td>{$row['registration_number']}</td>
                                <td>{$row['reminder_date']}</td>
                                <td>{$row['message']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>