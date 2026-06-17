<?php
session_start();
include "db.php";

// ✅ Ensure station logged in
if (!isset($_SESSION['station_id'])) {
    echo "<script>alert('Please login to continue'); window.location='station_login.php';</script>";
    exit;
}

$station_id = $_SESSION['station_id'];
$id = $_GET['id'];

// ✅ Fetch existing slot details
$sql = "SELECT * FROM charging_slots WHERE id='$id' AND station_id='$station_id'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Invalid Slot ID'); window.location='station_slot_view.php.php';</script>";
    exit;
}
$row = mysqli_fetch_assoc($result);

// ✅ Update slot
if (isset($_POST['update'])) {
    $slot_name = trim($_POST['slot_name']);
    $connector_type = trim($_POST['connector_type']);
    $power_kw = floatval($_POST['power_kw']);
    $price_per_kwh = floatval($_POST['price_per_kwh']);
    $auth_methods = implode(", ", $_POST['auth_methods']);

    // ✅ Validate values before updating
    if ($power_kw <= 0 || $price_per_kwh <= 0) {
        echo "<script>alert('⚠️ Power and Price must be greater than 0');</script>";
    } else {
        $update = "UPDATE charging_slots SET 
                    slot_name='$slot_name',
                    connector_type='$connector_type',
                    power_kw='$power_kw',
                    price_per_kwh='$price_per_kwh',
                    auth_methods='$auth_methods'
                    WHERE id='$id' AND station_id='$station_id'";

        if (mysqli_query($conn, $update)) {
            echo "<script>alert('✅ Slot updated successfully'); window.location='station_slot_view.php';</script>";
            exit;
        } else {
            echo "<script>alert('❌ Database error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Charging Slot</title>
<style>
    body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(to right, #16a34a, #15803d);
        margin: 0;
        padding: 0;
    }
    .container {
        width: 420px;
        margin: 60px auto;
        background: white;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    }
    h2 {
        color: #15803d;
        text-align: center;
        margin-bottom: 25px;
    }
    label {
        font-weight: 500;
        color: #065f46;
        display: block;
        margin-top: 12px;
    }
    input, select {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        margin-top: 5px;
        font-size: 14px;
    }
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 5px;
    }
    .checkbox-group label {
        font-weight: 400;
        color: #333;
    }
    button {
        width: 100%;
        padding: 12px;
        background: #16a34a;
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 8px;
        margin-top: 20px;
        cursor: pointer;
        transition: 0.3s ease;
    }
    button:hover {
        background: #15803d;
        transform: scale(1.02);
    }
    a {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #15803d;
        text-decoration: none;
        font-weight: 500;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Charging Slot</h2>
    <form method="POST" onsubmit="return validateSlotForm()">

        <label>Slot Name</label>
        <input type="text" name="slot_name" id="slot_name" value="<?php echo htmlspecialchars($row['slot_name']); ?>" required>

        <label>Charger Type (from Station)</label>
        <input type="text" name="charger_type" value="<?php echo htmlspecialchars($row['charger_type']); ?>" readonly>

        <label>Connector Type</label>
        <select name="connector_type" required>
            <option value="CCS 2" <?php if($row['connector_type']=='CCS 2') echo 'selected'; ?>>CCS 2</option>
            <option value="Type 2" <?php if($row['connector_type']=='Type 2') echo 'selected'; ?>>Type 2</option>
            <option value="CHAdeMO" <?php if($row['connector_type']=='CHAdeMO') echo 'selected'; ?>>CHAdeMO</option>
        </select>

        <label>Power (kW)</label>
        <input type="number" name="power_kw" id="power_kw" step="0.1" min="1" value="<?php echo $row['power_kw']; ?>" required oninput="validateNumber(this)">

        <label>Price per kWh (₹)</label>
        <input type="number" name="price_per_kwh" id="price_per_kwh" step="0.01" min="1" value="<?php echo $row['price_per_kwh']; ?>" required oninput="validateNumber(this)">

        <label>Authentication Methods</label>
        <div class="checkbox-group">
            <?php
            $methods = ["RFID", "Mobile App", "QR Code"];
            $saved_methods = explode(", ", $row['auth_methods']);
            foreach ($methods as $m) {
                $checked = in_array($m, $saved_methods) ? 'checked' : '';
                echo "<label><input type='checkbox' name='auth_methods[]' value='$m' $checked> $m</label>";
            }
            ?>
        </div>

        <button type="submit" name="update">Update Slot</button>
    </form>

    <a href="station_slot_view.php">← Back to Manage Slots</a>
</div>

<script>
function validateNumber(input) {
    if (input.value < 0.1) input.value = "";
}

function validateSlotForm() {
    let slotName = document.getElementById('slot_name').value.trim();
    let power = parseFloat(document.getElementById('power_kw').value);
    let price = parseFloat(document.getElementById('price_per_kwh').value);

    if (slotName === "") {
        alert("Slot name cannot be empty");
        return false;
    }
    if (isNaN(power) || power <= 0) {
        alert("⚠️ Power (kW) must be greater than 0");
        return false;
    }
    if (isNaN(price) || price <= 0) {
        alert("⚠️ Price (₹/kWh) must be greater than 0");
        return false;
    }
    return true;
}
</script>

</body>
</html>
