<?php
session_start();
include "db.php";

// ✅ Ensure station logged in
if (!isset($_SESSION['station_id'])) {
    echo "<script>alert('Please login to continue'); window.location='station_login.php';</script>";
    exit;
}

$station_id = $_SESSION['station_id'];

// ✅ Fetch charger type from stations table
$station_query = mysqli_query($conn, "SELECT charger_type FROM stations WHERE id='$station_id'");
$station_data = mysqli_fetch_assoc($station_query);
$charger_type = $station_data['charger_type'] ?? '';

// ✅ Handle form submission
if (isset($_POST['add_slot'])) {
    $slot_name      = trim($_POST['slot_name']);
    $connector_type = $_POST['connector_type'];
    $power_kw       = floatval($_POST['power_kw']);
    $price_per_kwh  = floatval($_POST['price_per_kwh']);
    $auth_methods   = isset($_POST['auth_methods']) ? implode(", ", $_POST['auth_methods']) : '';
    $status         = $_POST['status'];

    // 🚫 Back-end validation
    if ($power_kw <= 0 || $price_per_kwh <= 0) {
        echo "<script>alert('⚠️ Power and Price must be positive values!');</script>";
    } else {
        $sql = "INSERT INTO charging_slots 
                (station_id, slot_name, charger_type, connector_type, power_kw, price_per_kwh, auth_methods, status)
                VALUES ('$station_id', '$slot_name', '$charger_type', '$connector_type', '$power_kw', '$price_per_kwh', '$auth_methods', '$status')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('✅ Slot added successfully!'); window.location='station_slot_view.php';</script>";
        } else {
            echo "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Charging Slot</title>
<style>
    body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(to right, #80c399ff, #42ac69ff);
        margin: 0;
        padding: 0;
    }
    .page-wrapper {
        display: flex;
        justify-content: center;
        padding: 40px 0;
    }
    .container {
        background: #fff;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        width: 420px;
        text-align: center;
    }
    h2 {
        color: #15803d;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    label {
        display: block;
        text-align: left;
        margin-top: 10px;
        color: #065f46;
        font-weight: 500;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 4px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }
    input[readonly] {
        background-color: #f3f4f6;
        color: #555;
        cursor: not-allowed;
    }
    .checkbox-group {
        text-align: left;
        margin-top: 6px;
    }
    .checkbox-group label {
        display: inline-block;
        margin-right: 10px;
        font-weight: normal;
    }
    button {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background: #16a34a;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: 0.3s ease;
    }
    button:hover { background: #15803d; transform: scale(1.02); }
</style>
</head>
<body>

<h2 style="text-align:center; color:#fff; margin-top:30px;">Add Charging Slot</h2>

<div class="page-wrapper">
<div class="container">
<form method="POST" onsubmit="return validateSlotForm()">

    <label>Slot Name</label>
    <input type="text" name="slot_name" placeholder="e.g. Slot A1" required>

    <label>Charger Type</label>
    <input type="text" name="charger_type" value="<?php echo htmlspecialchars($charger_type); ?>" readonly>

    <label>Connector Type</label>
    <select name="connector_type" required>
        <option value="">Select Connector</option>
        <option value="CCS 2">CCS 2</option>
        <option value="Type 2">Type 2</option>
        <option value="CHAdeMO">CHAdeMO</option>
        <option value="GB/T">GB/T</option>
    </select>

    <label>Power (kW)</label>
    <input type="number" step="0.1" name="power_kw" id="power_kw" placeholder="e.g. 60" min="1" required>

    <label>Price (₹ per kWh)</label>
    <input type="number" step="0.01" name="price_per_kwh" id="price_per_kwh" placeholder="e.g. 8.50" min="1" required>

    <label>User Authentication Methods</label>
    <div class="checkbox-group">
        <label><input type="checkbox" name="auth_methods[]" value="RFID"> RFID</label>
        <label><input type="checkbox" name="auth_methods[]" value="Mobile App"> Mobile App</label>
        <label><input type="checkbox" name="auth_methods[]" value="QR Code"> QR Code</label>
    </div>

    <label>Status</label>
    <select name="status" required>
        <option value="Available">Available</option>
        <option value="Occupied">Occupied</option>
        <option value="Maintenance">Maintenance</option>
    </select>

    <button type="submit" name="add_slot">Add Slot</button>
</form>

<p style="margin-top:15px;">
    <a href="station_slot_view.php" style="color:#16a34a; font-weight:600;">Back to Slots</a>
</p>
</div>
</div>

<script>
// ✅ Front-end validation
function validateSlotForm() {
    let power = parseFloat(document.getElementById('power_kw').value);
    let price = parseFloat(document.getElementById('price_per_kwh').value);

    if (isNaN(power) || power <= 0) {
        alert("⚠️ Power (kW) must be greater than 0.");
        return false;
    }

    if (isNaN(price) || price <= 0) {
        alert("⚠️ Price per kWh must be greater than 0.");
        return false;
    }

    return true;
}
</script>

</body>
</html>
