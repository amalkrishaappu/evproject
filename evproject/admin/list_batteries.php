<?php
include '../config/db.php';
$result = mysqli_query($conn, "SELECT * FROM battery");
echo "<h2>Battery Inventory</h2><table border='1'>";
echo "<tr><th>ID</th><th>Brand</th><th>Model</th><th>Capacity</th><th>Dimension</th><th>Torque</th><th>Life</th><th>Condition</th><th>Status</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['battery_id']}</td>
        <td>{$row['brand']}</td>
        <td>{$row['model']}</td>
        <td>{$row['capacity']}</td>
        <td>{$row['dimension']}</td>
        <td>{$row['torque']}</td>
        <td>{$row['life_remaining']}%</td>
        <td>{$row['condition']}</td>
        <td>" . ($row['is_rented'] ? 'Rented' : 'Available') . "</td>
    </tr>";
}
echo "</table>";
