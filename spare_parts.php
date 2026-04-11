<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Spare Parts Inventory</h2>
    <a href="add_part.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Part</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Part Name</th>
            <th>Part Number</th>
            <th>In Stock</th>
            <th>Min Qty</th>
            <th>Unit Price</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT * FROM spare_parts ORDER BY part_name");
        while ($row = $stmt->fetch()) {
            $lowStock = $row['quantity_in_stock'] <= $row['minimum_quantity'] ? 'table-warning' : '';
            echo "<tr class='$lowStock'>
                <td>{$row['id']}</td>
                <td>{$row['part_name']}</td>
                <td>{$row['part_number']}</td>
                <td>{$row['quantity_in_stock']}</td>
                <td>{$row['minimum_quantity']}</td>
                <td>₹{$row['unit_price']}</td>
                <td>{$row['location']}</td>
                <td>
                    <a href='edit_part.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i></a>
                    <a href='delete_part.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='bi bi-trash'></i></a>
                </td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>