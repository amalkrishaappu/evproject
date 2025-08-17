<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "ev_project";

$conn = mysqli_connect($host, $user, $pass, $db);

if ($conn) {
    //echo "connected";
}
else
{
    //echo "not connected";
}
?>
