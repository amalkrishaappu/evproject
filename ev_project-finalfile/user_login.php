<?php
include('db.php');
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$error = "";

    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    // ✅ Insert login log
    $uid = $row['id'];
    mysqli_query($conn, "INSERT INTO user_logins (user_id, status) VALUES ($uid, 'active')");

    header("Location: user_home.php");
    exit();

    } else {
        $error = "❌ Invalid username or password!";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Login</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { background: linear-gradient(to bottom right, #e8f5e9, #a5d6a7); }
.card { background: white; border-top: 6px solid #2e7d32; }
label { font-weight: 600; color: #1b5e20; display: block; margin-bottom: 5px; }
.btn-green { background-color: #2e7d32; }
.btn-green:hover { background-color: #1b5e20; }
</style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

<form method="POST" class="card p-8 rounded-2xl shadow-2xl w-full max-w-md space-y-5">
  <h2 class="text-3xl font-bold text-center text-[#1b5e20] mb-4">User Login</h2>

  <?php if ($error): ?>
    <div class="bg-red-100 text-red-800 border border-red-600 p-3 rounded text-center"><?= $error ?></div>
  <?php endif; ?>

  <div>
    <label>Username</label>
    <input type="text" name="username" placeholder="Enter username" required
      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-700">
  </div>

  <div>
    <label>Password</label>
    <input type="password" name="password" placeholder="Enter password" required
      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-700">
  </div>

  <button type="submit" name="login" class="btn-green w-full py-3 text-white font-semibold rounded-lg shadow-md hover:scale-105 transition-all">
    Login
  </button>

  <p class="text-center text-gray-700 text-sm mt-3">
    Don’t have an account?
    <a href="user_register.php" class="text-green-800 font-semibold hover:underline">Register here</a>
  </p>
</form>
</body>
</html>
