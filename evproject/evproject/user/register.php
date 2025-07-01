<?php
include('../config/db.php');
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address=$_POST['address'];
    $phno=$_POST['phno'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql="insert into users(name,email,password,address,phoneno)values('$name','$email','$password','$address', '$phno')";
    if (mysqli_query($conn,$sql)) {
        echo "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Elegant Registration Form</title>
  <style>
    body {
      background: #f0f2f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    form {
      background: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    form input {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: border-color 0.3s;
      font-size: 1rem;
    }

    form input:focus {
      border-color: #007bff;
      outline: none;
    }

    form button {
      width: 100%;
      padding: 12px;
      background: #007bff;
      color: #fff;
      font-size: 1rem;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
      margin-top: 10px;
    }

    form button:hover {
      background: #0056b3;
    }

    @media (max-width: 500px) {
      form {
        padding: 1.5rem;
      }

      form input,
      form button {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>

  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="text" name="address" placeholder="Address" required><br>
    <input type="tel" name="phno" placeholder="Phone Number" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="register">Register</button>
  </form>

</body>
</html>

