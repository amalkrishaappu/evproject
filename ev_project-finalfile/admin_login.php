<?php
$admin_username = "admin";
$admin_password = "admin123";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $admin_username && $password === $admin_password) {
        header("Location: admin_dashboard.php?auth=1");
        exit;
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #bad7abff, #3e6744ff);
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .login-container {
    background: #ffffff;
    padding: 40px 35px;
    border-radius: 15px;
    box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.1);
    width: 360px;
    text-align: center;
    border: 2px solid #5daf5fff;
  }

  .login-container h2 {
    color: #1b5e20;
    margin-bottom: 25px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  label {
    display: block;
    text-align: left;
    font-size: 14px;
    color: #1b5e20;
    margin-bottom: 6px;
    font-weight: 500;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border: 1px solid #416142ff;
    border-radius: 8px;
    font-size: 15px;
    outline: none;
    transition: 0.3s;
    background-color: #f9fff9;
  }

  input[type="text"]:focus,
  input[type="password"]:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 5px #37723aff;
  }

  button {
    width: 100%;
    background: #4c884fff;
    color: white;
    border: none;
    padding: 12px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: 600;
    letter-spacing: 0.5px;
  }

  button:hover {
    background: #1b5e20;
    transform: scale(1.03);
  }

  .footer-text {
    margin-top: 20px;
    font-size: 13px;
    color: #555;
  }

  .footer-text a {
    color: #2e7d32;
    text-decoration: none;
    font-weight: 600;
  }

  .footer-text a:hover {
    text-decoration: underline;
  }

  .error-message {
    color: #c62828;
    background: #ffebee;
    border: 1px solid #ffcdd2;
    padding: 10px;
    border-radius: 8px;
    margin-top: 10px;
    font-size: 14px;
  }
</style>
</head>
<body>

  <div class="login-container">
    <h2>Admin Login</h2>
    <form action="" method="post">
      <label for="username">Username</label>
      <input type="text" name="username" placeholder="Enter Username" required>

      <label for="password">Password</label>
      <input type="password" name="password" placeholder="Enter Password" required>

      <button type="submit">LOGIN</button>

      <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
    </form>

    <div class="footer-text">
      <p>Back to <a href="index.html">Home</a></p>
    </div>
  </div>

</body>
</html>
