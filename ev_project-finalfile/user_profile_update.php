<?php
include('db.php');
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $age = intval($_POST['age']);
    $email = trim($_POST['email']);
    $phoneno = trim($_POST['phoneno']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);

    // ---------------- VALIDATION ----------------
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        die("<script>alert('❌ Name can contain only letters and spaces.'); window.history.back();</script>");
    }

    if ($age < 18) {
        die("<script>alert('❌ Age must be 18 or above.'); window.history.back();</script>");
    }

    if (!preg_match("/^[0-9]{10}$/", $phoneno)) {
        die("<script>alert('❌ Invalid phone number. Must be 10 digits.'); window.history.back();</script>");
    }

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/", $password)) {
        die("<script>alert('❌ Password must contain at least 1 uppercase, 1 lowercase, 1 number, 1 special character, and 8+ characters.'); window.history.back();</script>");
    }

    // ---------------- IMAGE UPLOAD ----------------
    $imageQuery = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
        $imageQuery = ", image='$targetFilePath'";
    }



    
    // ---------------- UPDATE QUERY ----------------
    $sql = "UPDATE users SET 
            name='$name', 
            username='$username', 
            age='$age', 
            email='$email', 
            phoneno='$phoneno', 
            address='$address', 
            password='$password' 
            $imageQuery 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Profile updated successfully!'); window.location='user_profile.php';</script>";
    } else {
        echo "<script>alert('❌ Error updating profile: " . $conn->error . "'); window.history.back();</script>";
    }

    $conn->close();
}
?>
