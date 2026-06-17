<?php
include('db.php');
session_start();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; 

    $query = mysqli_query($conn, "SELECT image FROM users WHERE id = $id");
    if ($row = mysqli_fetch_assoc($query)) {
        $imagePath = $row['image'];
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath); 
        }
    }

    $sql = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('User deleted successfully'); window.location.href='admin_view_user_details.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . mysqli_error($conn) . "'); window.location.href='admin_view_user_details.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='admin_view_user_details.php';</script>";
}
?>
