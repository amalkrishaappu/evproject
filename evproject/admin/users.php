<?php
include('../config/db.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}


$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<h2>Manage Users</h2>
<table border="1">
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Action</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a></td>
  </tr>
  <?php } ?>
</table>


