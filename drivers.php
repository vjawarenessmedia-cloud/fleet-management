<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Drivers</h2>
    <a href="add_driver.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Driver</a>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>License</th>
            <th>Phone</th>
            <th>Hired Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT * FROM drivers ORDER BY id DESC");
        while ($row = $stmt->fetch()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['license_number']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['hired_date']}</td>
                <td><span class='badge bg-" . ($row['status']=='active'?'success':'secondary') . "'>{$row['status']}</span></td>
                <td>
                    <a href='edit_driver.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i></a>
                    <a href='delete_driver.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='bi bi-trash'></i></a>
                </td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>