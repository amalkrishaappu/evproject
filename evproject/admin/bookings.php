<?php
include('../config/db.php');



$sql = "SELECT b.*, u.name as user_name, s.name as station_name 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN stations s ON b.station_id = s.id";
$result = mysqli_query($conn, $sql);
?>

<h2>Manage Bookings</h2>
<table border="1">
  <tr>
    <th>User</th>
    <th>Station</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?= $row['user_name'] ?></td>
    <td><?= $row['station_name'] ?></td>
    <td><?= $row['booking_date'] ?></td>
    <td><?= $row['booking_time'] ?></td>
    <td><?= $row['status'] ?></td>
  </tr>
  <?php } ?>
</table>


