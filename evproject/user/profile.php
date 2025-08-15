<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ev_project";

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('You must log in first'); window.location.href='login.php';</script>";
    exit();
}

$conn = mysqli_connect($host, $user, $pass, $db);

// Get user details from session email
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $name     = $row['name'];
    $phone    = $row['phoneno'];
    $address   = $row['address'];
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            padding: 40px;
        }
        .profile-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
        }
        .detail {
            margin: 10px 0;
            font-size: 18px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Welcome, <?php echo $name; ?> 👋</h2>
        <div class="detail"><span class="label">Email:</span> <?php echo $email; ?></div>
        <div class="detail"><span class="label">Phone:</span> <?php echo $phone; ?></div>
        <div class="detail"><span class="label">Skills:</span> <?php echo $address; ?></div>
        <!-- <div class="detail"><span class="label">User Type:</span> <?php echo $usertype; ?></div> -->
    </div>
</body>
</html>
