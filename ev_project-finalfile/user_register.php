<?php
include('db.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$success = "";
$error = "";

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $address = trim($_POST['address']);
    $phoneno = trim($_POST['phoneno']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

   $image = "";
if (!empty($_FILES['image']['name'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir);
    $imageName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $imageName;
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
    $image = $targetFilePath;
}



    // Insert data (plain password)
    $sql = "INSERT INTO users (name, age, address, phoneno, email, username, password, image) 
            VALUES ('$name', '$age', '$address', '$phoneno', '$email', '$username', '$password', '$image')";

    if (mysqli_query($conn, $sql)) {
        $success = "✅ Registration successful! Redirecting to login...";
        header("refresh:2; url=user_login.php");
    } else {
        $error = "Database error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EV User Registration</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { background: linear-gradient(to bottom right, #e8f5e9, #a5d6a7); }
.card { background: white; border-top: 6px solid #2e7d32; }
label { font-weight: 600; color: #1b5e20; margin-bottom: 4px; display: block; }
.btn-green { background-color: #2e7d32; }
.btn-green:hover { background-color: #1b5e20; }
.error-text { color: red; font-size: 0.9rem; display: none; }
input.error, textarea.error { border-color: red; }
</style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

<form id="registerForm" method="POST" enctype="multipart/form-data" class="card p-8 rounded-2xl shadow-2xl w-full max-w-lg space-y-4">
  <h2 class="text-3xl font-bold text-center text-[#1b5e20] mb-4">EV User Registration</h2>
  <p class="text-center text-gray-600 mb-4">Join our eco-friendly EV network 🌱</p>

  <?php if ($success): ?>
  <div class="bg-green-100 text-green-800 border border-green-600 p-3 rounded text-center"><?= $success ?></div>
  <?php endif; ?>

  <?php if ($error): ?>
  <div class="bg-red-100 text-red-800 border border-red-600 p-3 rounded text-center"><?= $error ?></div>
  <?php endif; ?>

  <div>
    <label>Full Name</label>
    <input type="text" name="name" id="name" placeholder="Enter your full name"
      required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-700">
    <p class="error-text" id="nameError">❌ Only letters and spaces allowed.</p>
  </div>

  <div>
    <label>Age</label>
    <input type="number" name="age" id="age" min="18" placeholder="Your age (18+)"
      required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-700">
    <p class="error-text" id="ageError">❌ Age must be 18 or older.</p>
  </div>

  <div>
    <label>Address</label>
    <textarea name="address" id="address" placeholder="Enter your complete address"
      required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-700"></textarea>
  </div>

  <div>
    <label>Phone Number</label>
    <input type="text" name="phoneno" id="phoneno" placeholder="Enter 10-digit number"
      required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-700">
    <p class="error-text" id="phoneError">❌ Enter a valid 10-digit number.</p>
  </div>

  <div>
    <label>Email</label>
    <input type="email" name="email" id="email" placeholder="Enter your email address"
      required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-700">
  </div>

  <div>
    <label>Username</label>
    <input type="text" name="username" id="username" placeholder="Create your username"
      required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-700">
  </div>

  <div>
    <label>Password</label>
    <input type="password" name="password" id="password" placeholder="1 uppercase, 1 special, 6+ chars"
      required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-700">
    <p class="error-text" id="passwordError">❌ Must include A-Z, a-z, number & special character.</p>
  </div>

  <div>
    <label>Profile Image</label>
    <input type="file" name="image" accept="image/*"
      class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-700">
  </div>

  <button type="submit" name="register" class="btn-green w-full py-3 text-white text-lg font-semibold rounded-lg shadow-md hover:scale-105 transition-all">
    Register Now
  </button>

  <p class="text-center text-gray-700 text-sm mt-3">
    Already registered? 
    <a href="user_login.php" class="text-green-800 font-semibold hover:underline">Login here</a>
  </p>
</form>

<script>
// Client-side validation
document.getElementById("registerForm").addEventListener("submit", function(e) {
    let valid = true;
    const name = document.getElementById("name");
    const phone = document.getElementById("phoneno");
    const password = document.getElementById("password");

    // Reset errors
    document.querySelectorAll(".error-text").forEach(el => el.style.display = "none");
    document.querySelectorAll("input, textarea").forEach(el => el.classList.remove("error"));

    if (!/^[A-Za-z ]+$/.test(name.value)) {
        document.getElementById("nameError").style.display = "block";
        name.classList.add("error");
        valid = false;
    }

    if (!/^\d{10}$/.test(phone.value)) {
        document.getElementById("phoneError").style.display = "block";
        phone.classList.add("error");
        valid = false;
    }

    const passRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{6,}$/;
    if (!passRegex.test(password.value)) {
        document.getElementById("passwordError").style.display = "block";
        password.classList.add("error");
        valid = false;
    }

    if (!valid) e.preventDefault();
});
</script>
</body>
</html>
