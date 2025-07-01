<?php
include('../config/db.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    //header("Location: login.php");
    //exit;
}


// Fetch stations
$sql = "SELECT * FROM station";
$result = mysqli_query($conn, $sql);
?>

<h2>Manage Charging Stations</h2>
<a href="add_station.php">Add New Station</a>
<table border="1">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Location</th>
    <th>Slots</th>
    <th>Available Slots</th>
    <th>Action</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['location'] ?></td>
    <td><?= $row['slot'] ?></td>
    <td><?= $row['available_slot'] ?></td>
    <td>
      <a href="edit_station.php?id=<?= $row['id'] ?>">Edit</a> |
      <a href="delete_station.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this station?')">Delete</a>
    </td>
  </tr>
  <?php } ?>
</table>

