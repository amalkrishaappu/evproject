<?php
include 'db.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM stations WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['status'] === 'approved') {


            $_SESSION['station_id'] = $row['id'];
            $_SESSION['station_name'] = $row['station_name'];


            header("Location: station_dashboard.php");
            exit();
        } else {
            $error = "Your account is pending admin approval.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EV Station Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  /* 💚 Soft green gradient background */
  background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
  background-size: cover;
  font-family: 'Segoe UI', sans-serif;
}

.login-container {
  background: rgba(255, 255, 255, 0.95);
  padding: 40px;
  border-radius: 15px;
  width: 380px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  animation: fadeIn 0.7s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

.login-container h3 {
  text-align: center;
  color: #198754; /* Bootstrap green */
  margin-bottom: 25px;
  font-weight: 600;
}

.form-control {
  border-radius: 10px;
}

.btn-login {
  background: #198754;
  color: white;
  border-radius: 10px;
  width: 100%;
  padding: 10px;
  font-weight: 500;
  transition: 0.3s;
}

.btn-login:hover {
  background: #146c43;
}

.error-box {
  background: #ffe6e6;
  color: red;
  padding: 10px;
  border-radius: 10px;
  text-align: center;
  margin-bottom: 15px;
  font-size: 14px;
}

a {
  text-decoration: none;
}
</style>
</head>

<body>

<div class="login-container">
  <h3>🔋 EV Station Login</h3>

  <?php if (!empty($error)) echo "<div class='error-box'>$error</div>"; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required placeholder="Enter username">
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required placeholder="Enter password">
    </div>
    <button type="submit" class="btn btn-login">Login</button>
  </form>

  <p class="text-center mt-3" style="font-size:14px;">
    Don’t have an account? <a href="station_register.php" style="color:#198754;font-weight:600;">Register here</a>
  </p>
</div>

</body>
</html>
