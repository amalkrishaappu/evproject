<?php
session_start();
include "db.php";

// ✅ Ensure station logged in
if (!isset($_SESSION['station_id'])) {
    echo "<script>alert('Please login to continue'); window.location='station_login.php';</script>";
    exit;
}

$station_id = $_SESSION['station_id'];

// ✅ Delete slot
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM charging_slots WHERE id='$id' AND station_id='$station_id'");
    echo "<script>alert('Slot deleted successfully'); window.location='station_slot_view.php';</script>";
    exit;
}

// ✅ Change status
if (isset($_POST['update_status'])) {
    $slot_id = $_POST['slot_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE charging_slots SET status='$status' WHERE id='$slot_id' AND station_id='$station_id'");
    echo "<script>alert('Status updated'); window.location='station_slot_view.php';</script>";
    exit;
}

// ✅ Fetch all slots
$slots = mysqli_query($conn, "SELECT * FROM charging_slots WHERE station_id='$station_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Charging Slots</title>
<style>
    body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(to right, #16a34a, #15803d);
        margin: 0;
        padding: 0;
    }
    /* --- NAVBAR --- */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(90deg, #9de4baff, #58c085ff);
        padding: 15px 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        color: #074226ff;
        font-weight: 600;
    }

    .navbar a {
        text-decoration: none;
        color: #d5f5e3;
        padding: 8px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .navbar a:hover {
        background: #fff;
        color: #1d8348;
    }

    .container {
        margin: 50px auto;
        width: 90%;
        max-width: 1100px;
        background: #fff;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        text-align: center;
    }

    h2 {
        color: #15803d;
        text-align: center;
        margin-bottom: 30px;
        text-transform: uppercase;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border-bottom: 1px solid #ddd;
        text-align: center;
        padding: 12px;
    }
    th {
        background: #16a34a;
        color: white;
        text-transform: uppercase;
    }
    tr:hover { background: #f1f1f1; }
    .btn {
        padding: 6px 10px;
        text-decoration: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }
    .edit { background: #3b82f6; color: white; }
    .delete { background: #dc2626; color: white; }
    .status { background: #facc15; color: black; }
    .add-btn {
        display: inline-block;
        margin-bottom: 20px;
        background: #15803d;
        color: white;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }
    .add-btn:hover { background: #166534; }

    select {
        padding: 5px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    /* --- BACK BUTTON (bottom center) --- */

    .back-btn {
    margin-top: 40px; /* instead of 30px */
    background: #f4f4f4ff;
    color: #166534;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s ease;
    }

    .back-btn:hover {
        background: #96c5a8ff;
        transform: scale(1.05);
    }

</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
  <div>
    <?php
    if (isset($_SESSION['station_name'])) {
        echo "Welcome, " . htmlspecialchars($_SESSION['station_name']);
    } else {
        echo "Welcome, Station Admin";
    }
    ?>
  </div>

  <div>
    <a href="station_profile.php">Profile</a>
    <a href="station_logout.php">Logout</a>
  </div>
</div>

<div class="container">
    <h2>Manage Charging Slots</h2>

    <a href="station_slot_add.php" class="add-btn">+ Add New Slot</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Slot Name</th>
            <th>Charger Type</th>
            <th>Connector</th>
            <th>Power (kW)</th>
            <th>Price (₹/kWh)</th>
            <th>Auth Methods</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php if (mysqli_num_rows($slots) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($slots)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['slot_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['charger_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['connector_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['power_kw']); ?></td>
                    <td><?php echo htmlspecialchars($row['price_per_kwh']); ?></td>
                    <td><?php echo htmlspecialchars($row['auth_methods']); ?></td>
                    <td>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="slot_id" value="<?php echo $row['id']; ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="Available" <?php if($row['status']=='Available') echo 'selected'; ?>>Available</option>
                                <option value="Occupied" <?php if($row['status']=='Occupied') echo 'selected'; ?>>Occupied</option>
                                <option value="Maintenance" <?php if($row['status']=='Maintenance') echo 'selected'; ?>>Maintenance</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td>
                        <a href="station_slot_edit.php?id=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
                        <a href="station_slot_view.php?delete=<?php echo $row['id']; ?>" 
                           class="btn delete" 
                           onclick="return confirm('Are you sure you want to delete this slot?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9" style="color:#666;">No slots found. Add your first one!</td></tr>
        <?php endif; ?>
    </table>

    
</div>

<!-- ✅ Back button below table (centered) -->
    <div style="text-align: center;">
        <a href="station_dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>

</body>
</html>
