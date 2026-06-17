<?php
session_start();
session_unset();    
session_destroy();  


header("Location: station_login.php"); 
exit;
?>
