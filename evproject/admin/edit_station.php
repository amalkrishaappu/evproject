<?php
include('../config/db.php');
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit;
// }

$id=$_GET['id'];
$sql = "select * from station where id='$id'";
$result = mysqli_query($conn, $sql);
$station = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $slots = $_POST['slots'];
    $available_slots = $_POST['available_slots'];
    $sql = "UPDATE station SET name='$name', location='$location', slot='$slots', available_slot='$available_slots' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: stations.php");
}


?>

<h2>Edit Station</h2>
<form method="POST">
  Name: <input type="text" name="name" value="<?= $station['name'] ?>" required><br>
  Location: <input type="text" name="location" value="<?= $station['location'] ?>" required><br>
  Slots: <input type="number" name="slots" value="<?= $station['slot'] ?>" required><br>
  Available Slots: <input type="number" name="available_slots" value="<?= $station['available_slot'] ?>" required><br>
  <button type="submit" name="update">Update Station</button>
</form>