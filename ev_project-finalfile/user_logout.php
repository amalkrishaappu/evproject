<?php
include('db.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    // ✅ Update the latest login record for this user
    $update = "
        UPDATE user_logins 
        SET logout_time = NOW(), status = 'logged_out' 
        WHERE user_id = $uid 
        ORDER BY id DESC 
        LIMIT 1
    ";
    mysqli_query($conn, $update);

    // ✅ Destroy session
    session_unset();
    session_destroy();
}

// ✅ Redirect to login page
header("Location: user_login.php");
exit();
?>
