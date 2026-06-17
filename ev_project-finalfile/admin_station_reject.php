<?php
include "db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "UPDATE stations SET status='rejected' WHERE id=$id";
    mysqli_query($conn, $query);
}

header("Location: adminviewstation.php");
exit;
?>
