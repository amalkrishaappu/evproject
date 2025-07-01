<?php
include('../config/db.php');
session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit;
// }

$id = $_GET['id'];
mysqli_query($conn, "delete from station where id='$id'");
header("Location: stations.php");
