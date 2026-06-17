<?php
include "db.php";
$result = mysqli_query($conn, "SELECT * FROM stations ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Charging Stations</title>
<style>
  body { 
    font-family: "Poppins", sans-serif; 
    background: linear-gradient(135deg, #d4fc79, #96e6a1); 
    margin: 0;
    padding: 0;
  }

  /* Navbar */
  .navbar {
    background: #1b5e20;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 40px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    position: sticky;
    top: 0;
    z-index: 10;
  }
  .navbar h1 {
    margin: 0;
    font-size: 22px;
    letter-spacing: 1px;
  }
  .navbar a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: 600;
    transition: 0.3s;
  }
  .navbar a:hover {
    color: #c8e6c9;
  }

  /* Page content */
  h2 {
    color: #0b6623;
    text-align: center;
    margin: 100px 0 20px;
    text-shadow: 1px 1px 2px #ffffff90;
  }

  .table-container {
    max-width: 100%;
    overflow-x: auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    padding: 20px;
    margin: 0 30px 50px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1100px;
  }

  th, td {
    text-align: center;
    padding: 12px;
    border-bottom: 1px solid #ddd;
  }

  th {
    background: #1b5e20;
    color: white;
    position: sticky;
    top: 0;
    z-index: 1;
  }

  tr:hover {
    background-color: #f1f8f4;
    transition: 0.3s;
  }

  /* Buttons */
  .btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
  }
  .btn-approve {
    background-color: #4caf50;
    color: white;
  }
  .btn-approve:hover {
    background-color: #43a047;
    transform: scale(1.05);
  }
  .btn-reject {
    background-color: #e53935;
    color: white;
  }
  .btn-reject:hover {
    background-color: #d32f2f;
    transform: scale(1.05);
  }

  /* Status styles */
  .status {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    text-transform: capitalize;
    display: inline-block;
  }
  .status.pending { background: #fff8e1; color: #fbc02d; border: 1px solid #fbc02d; }
  .status.approved { background: #e8f5e9; color: #2e7d32; border: 1px solid #2e7d32; }
  .status.rejected { background: #ffebee; color: #c62828; border: 1px solid #c62828; }

  .actions { display: flex; justify-content: center; gap: 8px; }

  /* Scrollbar styling */
  ::-webkit-scrollbar {
    height: 8px;
  }
  ::-webkit-scrollbar-thumb {
    background: #81c784;
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: #66bb6a;
  }
</style>
</head>
<body>

<!-- ✅ Navbar -->
<div class="navbar">
  <h1>⚡ EV Admin Panel</h1>
  <div>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_logout.php" style="background:#388e3c; padding:6px 12px; border-radius:6px;">Logout</a>
  </div>
</div>

<h2>Charging Stations List</h2>

<div class="table-container">
  <table>
    <tr>
      <th>ID</th>
      <th>Station Name</th>
      <th>Owner Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>District</th>
      <th>Latitude</th>
      <th>Longitude</th>
      <th>Charger Type</th>
      <th>Battery Rental</th>
      <th>Username</th>
      <th>Password</th>
      <th>Image</th>
      <th>Registered On</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
      <td><?= htmlspecialchars($row['id']) ?></td>
      <td><?= htmlspecialchars($row['station_name']) ?></td>
      <td><?= htmlspecialchars($row['owner_name']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td><?= htmlspecialchars($row['district']) ?></td>
      <td><?= htmlspecialchars($row['latitude']) ?></td>
      <td><?= htmlspecialchars($row['longitude']) ?></td>
      <td><?= htmlspecialchars($row['charger_type']) ?></td>
      <td><?= htmlspecialchars($row['battery_rental']) ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['password']) ?></td>
      <td>
        <?php if (!empty($row['image_path'])): ?>
          <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Image" width="60" height="60" style="border-radius:8px; object-fit:cover;">
        <?php else: ?>
          <span style="color:gray;">No Image</span>
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($row['registered_on']) ?></td>
      <td>
        <?php 
          $status = $row['status'] ?? 'pending';
          if ($status == 'approved') echo '<span class="status approved">Approved</span>';
          elseif ($status == 'rejected') echo '<span class="status rejected">Rejected</span>';
          else echo '<span class="status pending">Pending</span>';
        ?>
      </td>
      <td class="actions">
        <?php if ($status == 'pending'): ?>
          <a href="admin_station_approve.php?id=<?= $row['id'] ?>" class="btn btn-approve" onclick="return confirm('Approve this station?')">Approve</a>
          <a href="admin_station_reject.php?id=<?= $row['id'] ?>" class="btn btn-reject" onclick="return confirm('Reject this station?')">Reject</a>
        <?php elseif ($status == 'approved'): ?>
          <span class="status approved">Approved</span>
        <?php elseif ($status == 'rejected'): ?>
          <span class="status rejected">Rejected</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php } ?>
  </table>
</div>

</body>
</html>
