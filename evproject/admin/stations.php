<?php
include('../config/db.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    // header("Location: login.php");
    // exit;
}

// Fetch stations
$sql = "SELECT * FROM station";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Charging Stations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f7f9;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    a.button {
      display: inline-block;
      margin-bottom: 15px;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      transition: background 0.3s ease;
    }

    a.button:hover {
      background-color: #0056b3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #007bff;
      color: white;
    }

    td a {
      color: #007bff;
      text-decoration: none;
    }

    td a:hover {
      text-decoration: underline;
    }

    @media screen and (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      tr {
        margin-bottom: 15px;
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 10px;
      }

      th {
        display: none;
      }

      td {
        position: relative;
        padding-left: 50%;
      }

      td::before {
        position: absolute;
        top: 12px;
        left: 10px;
        width: 45%;
        white-space: nowrap;
        font-weight: bold;
        color: #333;
      }

      td:nth-child(1)::before { content: "ID"; }
      td:nth-child(2)::before { content: "Name"; }
      td:nth-child(3)::before { content: "Location"; }
      td:nth-child(4)::before { content: "Slots"; }
      td:nth-child(5)::before { content: "Available Slots"; }
      td:nth-child(6)::before { content: "Action"; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Manage Charging Stations</h2>
    <a class="button" href="add_station.php">+ Add New Station</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Location</th>
          <th>Slots</th>
          <th>Available Slots</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['location'] ?></td>
            <td><?= $row['slot'] ?></td>
            <td><?= $row['available_slot'] ?></td>
            <td>
              <a href="edit_station.php?id=<?= $row['id'] ?>">Edit</a> |
              <a href="delete_station.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this station?')">Delete</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>
