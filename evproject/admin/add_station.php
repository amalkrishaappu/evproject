<?php
include('../config/db.php');
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     // header("Location: login.php");
//     // exit;
// }

if (isset($_POST['add'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $slots = $_POST['slots'];
    $av_slots = $_POST['available'];

    $sql = "INSERT INTO station(id, name, location, slot, available_slot) VALUES ('$id', '$name', '$location', '$slots', '$av_slots')";
    mysqli_query($conn, $sql);
    header("Location: stations.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Charging Station</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f2f5f7;
      margin: 0;
      padding: 20px;
    }

    .form-container {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
    }

    form label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #555;
    }

    form input[type="text"],
    form input[type="number"] {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
      transition: border 0.3s;
    }

    form input:focus {
      border-color: #007bff;
      outline: none;
    }

    button {
      width: 100%;
      background-color: #007bff;
      color: white;
      border: none;
      padding: 12px;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    @media (max-width: 600px) {
      .form-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Add Charging Station</h2>
  <form method="POST">
    <label for="id">Station ID:</label>
    <input type="text" name="id" required placeholder="Enter Station ID">

    <label for="name">Name:</label>
    <input type="text" name="name" required placeholder="Enter Station Name">

    <label for="location">Location:</label>
    <input type="text" name="location" required placeholder="Enter Location">

    <label for="slots">Total Slots:</label>
    <input type="number" name="slots" required placeholder="Enter Total Slots">

    <label for="available">Available Slots:</label>
    <input type="number" name="available" required placeholder="Enter Available Slots">

    <button type="submit" name="add">Add Station</button>
  </form>
</div>

</body>
</html>
