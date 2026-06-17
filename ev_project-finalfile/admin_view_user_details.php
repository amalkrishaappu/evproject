<?php
include 'db.php';

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Management</title>
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #e8f5e9, #c8e6c9);
    margin: 0;
    padding: 0;
  }

  .container {
    width: 90%;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }

  h2 {
    text-align: center;
    color: #2e7d32;
    margin-bottom: 30px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
    font-size: 15px;
  }

  table th, table td {
    padding: 12px;
    border-bottom: 1px solid #a5d6a7;
  }

  table th {
    background-color: #388e3c;
    color: white;
    text-transform: uppercase;
  }

  table tr:nth-child(even) {
    background-color: #f1f8e9;
  }

  table tr:hover {
    background-color: #dcedc8;
    transition: 0.3s;
  }

  .delete-btn {
    background: #c62828;
    color: white;
    padding: 7px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s;
  }

  .delete-btn:hover {
    background: #b71c1c;
  }

  .footer {
    text-align: center;
    margin-top: 20px;
  }

  .footer a {
    text-decoration: none;
    background: #2e7d32;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    transition: 0.3s;
  }

  .footer a:hover {
    background: #1b5e20;
  }
</style>
</head>
<body>

<div class="container">
  <h2>Registered Users</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Age</th>
      <th>Address</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Username</th>
      <th>Password</th>
      <th>Image</th>
      <th>Created At</th>
      <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['age']) ?></td>
      <td><?= htmlspecialchars($row['address']) ?></td>
      <td><?= htmlspecialchars($row['phoneno']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['password']) ?></td>
      <td>
        <?php if (!empty($row['image'])): ?>
          <img src="<?= htmlspecialchars($row['image']) ?>" alt="User Image" width="50" height="50" style="border-radius: 50%;">
        <?php else: ?>
          No Image
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($row['created_at']) ?></td>
      <td>
       <a  class="delete-btn" href="admin_delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
      </td>
    </tr>
    <?php } ?>
  </table>

  <div class="footer">
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a>
  </div>
</div>

</body>
</html>
