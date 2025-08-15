<?php
include('../config/db.php');


$id = $_GET['id'];
mysqli_query($conn, "delete from station where id='$id'");
header("Location: stations.php");
