<?php
$admin_username = "admin";
$admin_password = "admin123";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $admin_username && $password === $admin_password) {
        // Instead of using session, pass a flag via GET
        header("Location: admin.php?auth=1");
        exit;
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>

<h2>Admin Login</h2>

<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<?php if ($error): ?>
<p style='color:red;'><?= $error ?></p>
<?php endif; ?>
