<?php
include "db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "UPDATE stations SET status='approved' WHERE id=$id";
    mysqli_query($conn, $query);
}

header("Location: admin_view_stations.php");
exit;
?>

