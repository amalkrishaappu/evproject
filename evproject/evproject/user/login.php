<?php
session_start(); // Start the session

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ev_project";

$conn = mysqli_connect($host, $user, $pass, $db);
$msg = ""; // Initialize message

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass  = $_POST['pass'];

    // Optional: where to redirect after login

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row['password'])) {
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $row['id'];

            // Redirect to desired page
            header("Location:user_dashboard.php");
            exit();
        } else {
            $msg = "❌ Incorrect password!";
        }
    } else {
        $msg = "❌ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EV Charging - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f5ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .msg {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .form-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9em;
        }

        .form-footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Login</h2>

    <?php if (!empty($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="pass" placeholder="Password" required>

        <!-- Dynamically set where to go after login -->
        <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_GET['next'] ?? 'profile.php') ?>">

        <input type="submit" name="login" value="Login">
    </form>

    <div class="form-footer">
        New user? <a href="register.html">Register here</a>
    </div>
</div>

</body>
</html>
