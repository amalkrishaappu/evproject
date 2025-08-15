<?php
session_start();
session_unset();    // remove all session variables
session_destroy();  // destroy the session

// Redirect to login page or homepage
header("Location: login.php"); // Or "index.html" if you prefer
exit;
?>