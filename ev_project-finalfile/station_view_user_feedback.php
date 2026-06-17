<?php
include 'db.php';
session_start();

$sql = "SELECT f.*, u.name AS user_name 
        FROM feedback f 
        LEFT JOIN users u ON f.user_id = u.id 
        ORDER BY f.date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EV Station Feedback & Complaints</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
:root {
  --primary: #15803d;     /* dark green */
  --primary-light: #22c55e; /* lighter green */
  --accent: #bbf7d0;       /* pale green */
  --bg: #f1fdf4;          /* background */
  --text-dark: #0b2e13;   /* text */
  --table-bg: #e8f5e9;
}

body {
  font-family: 'Poppins', sans-serif;
  background: var(--bg);
  margin: 0;
  padding: 0;
  color: var(--text-dark);
}

/* ✅ Navbar */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(90deg, #15803d, #22c55e);
  padding: 15px 35px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.25);
  color: white;
  font-weight: 600;
  position: sticky;
  top: 0;
  z-index: 100;
}

.navbar div {
  display: flex;
  align-items: center;
  gap: 15px;
}

.navbar span {
  font-size: 17px;
  letter-spacing: 0.5px;
}

.navbar a {
  text-decoration: none;
  color: white;
  padding: 8px 14px;
  border-radius: 6px;
  font-weight: 500;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.1);
}

.navbar a:hover {
  background: white;
  color: var(--primary);
  box-shadow: 0 0 10px rgba(255,255,255,0.6);
  transform: translateY(-2px);
}

/* ✅ Page Heading */
h2 {
  text-align: center;
  margin: 30px 0;
  color: var(--primary);
  font-weight: 700;
}

/* ✅ Table */
.table-container {
  width: 90%;
  margin: 0 auto 50px auto;
  background: var(--table-bg);
  border-radius: 12px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  border: 2px solid var(--accent);
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  text-align: left;
  padding: 14px 16px;
  border-bottom: 1px solid #c8e6c9;
  font-size: 15px;
}

th {
  background: var(--primary);
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  font-size: 14px;
}

tr:nth-child(even) { background: #f9fff9; }
tr:hover { background: #d9f7de; transition: 0.3s; }

.type-feedback { color: #065f46; font-weight: 600; }
.type-complaint { color: #c62828; font-weight: 600; }

/* ✅ Back button */
.back-btn {
  display: inline-block;
  margin: 25px auto;
  padding: 10px 26px;
  background: var(--primary);
  color: #fff;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  box-shadow: 0 3px 8px rgba(0,0,0,0.25);
  transition: 0.3s;
}
.back-btn:hover {
  background: var(--primary-light);
  transform: translateY(-2px);
}

/* ✅ Footer (optional aesthetic) */
.footer {
  text-align: center;
  font-size: 13px;
  color: #555;
  margin-bottom: 15px;
}
</style>
</head>
<body>

<!-- ✅ Navbar -->
<div class="navbar">
  <div>
    <span>
      <?php
      if (isset($_SESSION['station_name'])) {
          echo "Welcome, " . htmlspecialchars($_SESSION['station_name']);
      } else {
          echo "Welcome, Station Admin";
      }
      ?>
    </span>
  </div>

  <div>
    <a href="station_dashboard.php">🏠 Dashboard</a>
    <a href="station_logout.php">🚪 Logout</a>
  </div>
</div>

<!-- ✅ Title -->
<h2>💬 Users Feedback & Complaints</h2>

<!-- ✅ Table -->
<div class="table-container">
  <table>
    <tr>
      <th>User</th>
      <th>Title</th>
      <th>Type</th>
      <th>Rating</th>
      <th>Message</th>
      <th>Date</th>
    </tr>
    <?php
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $typeClass = strtolower($row['type']) === 'complaint' ? 'type-complaint' : 'type-feedback';
        echo "<tr>
                <td>".htmlspecialchars($row['user_name'])."</td>
                <td>".htmlspecialchars($row['title'])."</td>
                <td class='{$typeClass}'>".htmlspecialchars(ucfirst($row['type']))."</td>
                <td>".htmlspecialchars($row['rating'])."</td>
                <td>".htmlspecialchars($row['message'])."</td>
                <td>".htmlspecialchars($row['date'])."</td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='6' style='text-align:center;'>No feedback records found.</td></tr>";
    }
    ?>
  </table>
</div>


</body>
</html>
