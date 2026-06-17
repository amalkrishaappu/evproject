<?php
session_start();
include "db.php";

// ✅ Check login
if (!isset($_SESSION['station_id'])) {
    echo "<script>alert('Please login to continue'); window.location='station_login.php';</script>";
    exit;
}

$station_id = $_SESSION['station_id'];

// ✅ Get battery ID
if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid battery ID'); window.location='station_battery_view.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM battery_rentals WHERE id='$id' AND station_id='$station_id'");
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Battery not found'); window.location='station_battery_view.php';</script>";
    exit;
}

$battery = mysqli_fetch_assoc($result);

// ✅ Update battery
if (isset($_POST['update'])) {
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
    $image = $battery['image']; // Keep existing if not replaced

    // ✅ Server-side Validation
    $errors = [];
    if (!preg_match("/^[A-Za-z ]+$/", $brand)) $errors[] = "Brand should contain only letters and spaces.";
    if (!is_numeric($capacity) || $capacity <= 0) $errors[] = "Capacity must be a positive number.";
    if (!is_numeric($voltage) || $voltage <= 0) $errors[] = "Voltage must be a positive number.";
    if (!is_numeric($range_km) || $range_km <= 0) $errors[] = "Range must be a positive number.";
    if (!is_numeric($life) || $life <= 0) $errors[] = "Life must be a positive number.";
    if (!is_numeric($rent_price) || $rent_price <= 0) $errors[] = "Rent Price must be greater than 0.";
    if (!is_numeric($stock_count) || $stock_count < 1) $errors[] = "Stock count must be at least 1.";

    // ✅ Image upload if new
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $new_filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_filename;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $new_filename;
        } else {
            $errors[] = "Failed to upload image.";
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE battery_rentals SET 
                    brand='$brand', model='$model', capacity='$capacity',
                    voltage='$voltage', compatibility='$compatibility',
                    range_km='$range_km', `condition`='$condition',
                    life='$life', rent_price='$rent_price',
                    stock_count='$stock_count', image='$image'
                WHERE id='$id' AND station_id='$station_id'";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Battery updated successfully'); window.location='station_battery_view.php';</script>";
            exit;
        } else {
            $errors[] = "Database update failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Battery Details</title>
<style>
    body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(to right, #16a34a, #15803d);
        margin: 0;
        padding: 0;
    }

    .container {
        background: #fff;
        width: 90%;
        max-width: 700px;
        margin: 60px auto;
        border-radius: 14px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        padding: 30px;
    }

    h2 {
        color: #15803d;
        text-align: center;
        margin-bottom: 25px;
    }

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
    }

    input, select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
    }

    input[type="file"] {
        border: none;
    }

    .btn {
        background: #15803d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        width: 100%;
        transition: 0.3s ease;
    }

    .btn:hover {
        background: #166534;
        transform: scale(1.02);
    }

    .error {
        color: red;
        margin-bottom: 15px;
        font-size: 14px;
    }

    img {
        display: block;
        margin: 10px auto 20px;
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }
</style>

<script>
function validateForm() {
    const brand = document.forms["batteryForm"]["brand"].value.trim();
    const numFields = ["capacity","voltage","range_km","life","rent_price","stock_count"];
    const namePattern = /^[A-Za-z ]+$/;
    let valid = true;

    document.querySelectorAll('.error').forEach(e => e.innerHTML = "");

    if (!namePattern.test(brand)) {
        document.getElementById("brandError").innerHTML = "Brand should contain only letters and spaces.";
        valid = false;
    }

    numFields.forEach(id => {
        const val = parseFloat(document.forms["batteryForm"][id].value);
        if (isNaN(val) || val <= 0) {
            document.getElementById(id + "Error").innerHTML = "Must be a positive number.";
            valid = false;
        }
    });

    return valid;
}
</script>
</head>
<body>

<div class="container">
    <h2>Edit Battery Details</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e) echo "• $e<br>"; ?>
        </div>
    <?php endif; ?>

    <form name="batteryForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <label>Brand:</label>
        <input type="text" name="brand" placeholder="Ex: Exide" value="<?php echo htmlspecialchars($battery['brand']); ?>">
        <div id="brandError" class="error"></div>

        <label>Model:</label>
        <input type="text" name="model" placeholder="Ex: EM-12" value="<?php echo htmlspecialchars($battery['model']); ?>">

        <label>Capacity (Ah):</label>
        <input type="number" name="capacity" placeholder="Ex: 100"  min="1" value="<?php echo htmlspecialchars($battery['capacity']); ?>">
        <div id="capacityError" class="error"></div>

        <label>Voltage (V):</label>
        <input type="number" name="voltage" placeholder="Ex: 48" min="1" value="<?php echo htmlspecialchars($battery['voltage']); ?>">
        <div id="voltageError" class="error"></div>

        <label>Compatibility:</label>
        <select name="compatibility" required>
            <option value="">--Select--</option>
            <option value="2 Wheeler" <?php if($battery['compatibility']=='2 Wheeler') echo 'selected'; ?>>2 Wheeler</option>
            <option value="3 Wheeler" <?php if($battery['compatibility']=='3 Wheeler') echo 'selected'; ?>>3 Wheeler</option>
            <option value="4 Wheeler" <?php if($battery['compatibility']=='4 Wheeler') echo 'selected'; ?>>4 Wheeler</option>
        </select>

        <label>Range (km):</label>
        <input type="number" name="range_km" placeholder="Ex: 120" min="1" value="<?php echo htmlspecialchars($battery['range_km']); ?>">
        <div id="range_kmError" class="error"></div>

        <label>Condition:</label>
        <select name="condition">
            <option value="New" <?php if($battery['condition']=='New') echo 'selected'; ?>>New</option>
            <option value="Used" <?php if($battery['condition']=='Used') echo 'selected'; ?>>Used</option>
            <option value="Reconditioned" <?php if($battery['condition']=='Reconditioned') echo 'selected'; ?>>Reconditioned</option>
        </select>

        <label>Life (cycles/Years):</label>
        <input type="text" name="life" placeholder="Ex: 800" value="<?php echo htmlspecialchars($battery['life']); ?>">
        <div id="lifeError" class="error"></div>

        <label>Rent Price (₹):</label>
        <input type="number" step="0.01" name="rent_price" min="1" placeholder="Ex: 250.00" value="<?php echo htmlspecialchars($battery['rent_price']); ?>">
        <div id="rent_priceError" class="error"></div>

        <label>Stock Count:</label>
        <input type="number" name="stock_count" placeholder="Ex: 5" min="1" value="<?php echo htmlspecialchars($battery['stock_count']); ?>">
        <div id="stock_countError" class="error"></div>

        <label>Current Image:</label>
        <?php if (!empty($battery['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($battery['image']); ?>" alt="Battery Image">
        <?php endif; ?>

        <label>Change Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update" class="btn">Update Battery</button>
    </form>
</div>

</body>
</html>
