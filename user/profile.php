<?php
// Start the session at the very beginning
session_start();

// Include your database connection
include('../config/db.php');

// 1. CHECK IF USER IS LOGGED IN
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit(); // Always exit after a redirect
}

// 2. SECURELY FETCH USER DATA
// This SQL query has ONE '?' placeholder
$sql = "SELECT name, address, phone_number, email, image FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

// Check if the SQL preparation was successful
if ($stmt) {
    // This bind_param call provides ONE variable to match the '?' above
    // THIS IS THE LINE THAT FIXES THE ERROR
    $stmt->bind_param("s", $_SESSION['email']);
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "Error: User associated with this session was not found.";
        exit();
    }
    $stmt->close();
} else {
    echo "Database query failed to prepare.";
    exit();
}

// 3. SET THE PROFILE IMAGE PATH
$imagePath = "../uploads/" . htmlspecialchars($user['image']);
if (empty($user['image']) || !file_exists($imagePath)) {
    // Make sure you have a default image located at 'images/default.png'
    $imagePath = "images/default.png";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <style>
        /* Your CSS styles remain unchanged */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .profile-box {
            background: #fff;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #123e79;
            margin-bottom: 25px;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 25px auto;
            border: 3px solid #007bff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .info {
            margin: 15px 0;
            text-align: left;
        }
        .label {
            font-weight: bold;
            color: #333;
            display: inline-block;
            min-width: 90px;
        }
        .info a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="profile-box">
        <img src="<?php echo $imagePath; ?>" class="profile-img" alt="Profile Image">

        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>

        <div class="info"><span class="label">Address:</span> <?php echo htmlspecialchars($user['address']); ?></div>
        
        <div class="info"><span class="label">Phone:</span> <?php echo htmlspecialchars($user['phone_number']); ?></div>
        
        <div class="info"><span class="label">Username:</span> <?php echo htmlspecialchars($user['name']); ?></div>
        <div class="info"><span class="label">Email:</span> <?php echo htmlspecialchars($user['email']); ?></div>
        <div class="info" style="margin-top: 20px;"><a href="logout.php">Logout</a></div>
    </div>
</body>
</html>