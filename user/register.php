<?php
include('../config/db.php'); // Database connection

if (isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $address  = trim($_POST['address']);
    $phno     = trim($_POST['phno']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $message = '';

    // Validate image upload
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $targetDir  = "../uploads/";
        $imageName  = time() . "_" . basename($_FILES['image']['name']); // Unique name
        $targetPath = $targetDir . $imageName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // Prepared statement
            $sql  = "INSERT INTO users (name, address, phone_number, email, password, image) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssssss", $name, $address, $phno, $email, $password, $imageName);

                if (mysqli_stmt_execute($stmt)) {
                    $message = "Registration successful! ✅";
                } else {
                    $message = "Error: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $message = "Database error: " . mysqli_error($conn);
            }
        } else {
            $message = "Image upload failed. Check folder permissions.";
        }
    } else {
        $message = "Please select a profile image.";
    }

    echo "<script>alert('" . htmlspecialchars($message) . "');</script>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Elegant Registration Form</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Font: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* Custom styles for Inter font and to ensure full height */
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

  <!-- Added enctype="multipart/form-data" for file uploads -->
  <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-md">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Register Now</h2>

    <input
      type="text"
      name="name"
      placeholder="Full Name"
      required
      class="w-full px-5 py-3 my-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg transition-all duration-300 ease-in-out placeholder-gray-500"
    >
    <input
      type="text"
      name="address"
      placeholder="Address"
      required
      class="w-full px-5 py-3 my-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg transition-all duration-300 ease-in-out placeholder-gray-500"
    >
    <input
      type="tel"
      name="phno"
      placeholder="Phone Number"
      required
      class="w-full px-5 py-3 my-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg transition-all duration-300 ease-in-out placeholder-gray-500"
    >
    <!-- Changed type to 'file' and name to 'profile_image' for clarity -->
    <input
      type="file"
      name="image"
      accept="image/*"
      class="w-full px-5 py-3 my-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg transition-all duration-300 ease-in-out file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
    >
    <input
      type="email"
      name="email"
      placeholder="Email"
      required
      class="w-full px-5 py-3 my-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg transition-all duration-300 ease-in-out placeholder-gray-500"
    >
    <input
      type="password"
      name="password"
      placeholder="Password"
      required
      class="w-full px-5 py-3 my-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg transition-all duration-300 ease-in-out placeholder-gray-500"
    >
    <button
      type="submit"
      name="register"
      class="w-full py-3 mt-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xl font-semibold rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-300 ease-in-out transform hover:scale-105"
    >
      Register
    </button>
  </form>

</body>
</html>
