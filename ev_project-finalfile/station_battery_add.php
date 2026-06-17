<?php
session_start();
include "db.php";

// ✅ Check station login
if (!isset($_SESSION['station_id'])) {
    echo "<script>alert('Please login to continue'); window.location='station_login.php';</script>";
    exit;
}

$station_id = $_SESSION['station_id'];

if (isset($_POST['add'])) {
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $capacity = trim($_POST['capacity']);
    $voltage = trim($_POST['voltage']);
    $compatibility = $_POST['compatibility'];
    $range_km = trim($_POST['range_km']);
    $condition = $_POST['condition'];
    $life = trim($_POST['life']);
    $rent_price = trim($_POST['rent_price']);
    $stock_count = trim($_POST['stock_count']);
    $status = "Available";

    // ✅ Backend validation
    if (!preg_match("/^[A-Za-z ]+$/", $brand)) {
        echo "<script>alert('Brand name should contain only letters and spaces');</script>";
    } elseif ($rent_price <= 0 || $stock_count <= 0) {
        echo "<script>alert('Rent price and stock count must be greater than 0');</script>";
    } else {
        $imagePath = "";
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/";
            $fileName = time() . "_" . basename($_FILES['image']['name']);
            $targetPath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $fileName;
            }
        }

        $sql = "INSERT INTO battery_rentals (station_id, brand, model, capacity, voltage, compatibility, range_km, `condition`, life, rent_price, stock_count, image, status)
                VALUES ('$station_id', '$brand', '$model', '$capacity', '$voltage', '$compatibility', '$range_km', '$condition', '$life', '$rent_price', '$stock_count', '$imagePath', '$status')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('✅ Battery added successfully'); window.location='station_battery_view.php';</script>";
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
<title>Add Battery for Rental</title>
<style>
    body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(to right, #16a34a, #15803d);
        margin: 0;
        padding: 40px 0;
        min-height: 100vh;
    }
    .container {
        background: #fff;
        width: 90%;
        max-width: 550px;
        border-radius: 14px;
        padding: 30px 35px;
        margin: 0 auto;
        box-shadow: 0 6px 14px rgba(0,0,0,0.2);
    }
    h2 {
        color: #15803d;
        text-align: center;
        margin-bottom: 25px;
        text-transform: uppercase;
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
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-top: 5px;
        font-size: 14px;
    }
    button {
        width: 100%;
        padding: 12px;
        background: #16a34a;
        color: white;
        border: none;
        border-radius: 8px;
        margin-top: 25px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s ease;
    }
    button:hover {
        background: #15803d;
        transform: scale(1.03);
    }
    .back-btn {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #15803d;
        text-decoration: none;
        font-weight: 600;
    }
    small {
        color: red;
        display: none;
        font-size: 13px;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Add Battery for Rent</h2>
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateBatteryForm()">

        <label>Brand</label>
        <input type="text" name="brand" id="brand" min="1" placeholder="Ex: Exide, Amaron, Okaya" required>
        <small id="brandError">Brand name should contain only letters and spaces.</small>

        <label>Model</label>
        <input type="text" name="model" id="model" min="1" placeholder="Ex: XP-5000, EVX-12, LFP72" required>

        <label>Capacity (Ah)</label>
        <input type="number" name="capacity" id="capacity" min="1" placeholder="Ex: 80Ah, 120Ah" required>

        <label>Voltage (V)</label>
        <input type="number" name="voltage" id="voltage" min="1" placeholder="Ex: 48V, 72V, 96V" required>

        <label>Compatibility</label>
        <select name="compatibility" required>
            <option value="">Select Vehicle Type</option>
            <option value="2 Wheeler">2 Wheeler</option>
            <option value="3 Wheeler">3 Wheeler</option>
            <option value="4 Wheeler">4 Wheeler</option>
        </select>

        <label>Range (km)</label>
        <input type="number" name="range_km" id="range_km" min="1" placeholder="Ex: 100 km per charge" required>

        <label>Condition</label>
        <select name="condition" required>
            <option value="New">New</option>
            <option value="Used">Used</option>
            <option value="Reconditioned">Reconditioned</option>
        </select>

        <label>Life (cycles / years)</label>
        <input type="text" name="life" id="life" placeholder="Ex: 1000 cycles or 3 years" required>

        <label>Rent Price (₹)</label>
        <input type="number" name="rent_price" id="rent_price" step="0.01" min="1" placeholder="Ex: 250.00" required>

        <label>Stock Count</label>
        <input type="number" name="stock_count" id="stock_count" min="1" placeholder="Ex: 5" required>

        <label>Image</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" name="add">Add Battery</button>
    </form>

    <a href="station_battery_view.php" class="back-btn">← Back to Battery List</a>
</div>

<script>
function validateBatteryForm() {
    const brand = document.getElementById("brand").value.trim();
    const brandError = document.getElementById("brandError");
    const price = parseFloat(document.getElementById("rent_price").value);
    const stock = parseInt(document.getElementById("stock_count").value);

    let valid = true;

    // ✅ Brand validation (only letters and spaces)
    if (!/^[A-Za-z ]+$/.test(brand)) {
        brandError.style.display = "block";
        valid = false;
    } else {
        brandError.style.display = "none";
    }

    // ✅ Price validation
    if (isNaN(price) || price <= 0) {
        alert("Rent price must be greater than 0");
        valid = false;
    }

    // ✅ Stock validation
    if (isNaN(stock) || stock <= 0) {
        alert("Stock count must be greater than 0");
        valid = false;
    }

    return valid;
}
</script>

</body>
</html>
