<?php
session_start();
include "db.php";

$id = $_GET['id'];

// Fetch station data
$sql = "SELECT * FROM stations WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $station_name  = $_POST['station_name'];
    $owner_name    = $_POST['owner_name'];
    $email         = $_POST['email'];
    $phone         = $_POST['phone'];
    $district      = $_POST['district'];
    $location      = $_POST['location'];
    $latitude      = $_POST['latitude'];
    $longitude     = $_POST['longitude'];
    $charger_type  = $_POST['charger_type'];
    $battery_rental = $_POST['battery_rental'];
    $username      = $_POST['username'];
    $password      = $_POST['password'];

 // ✅ Keep old image if not changed
$imageUpdate = $row['image_path'];

if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $targetDir = "uploads/";
    $targetPath = $targetDir . $imageName;

    // Move the uploaded file to uploads folder
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imageUpdate = $targetPath; // ✅ Save full path like "uploads/xxxx.jpg"
    }
}


    // ✅ Update DB
    $sql = "UPDATE stations SET 
            station_name='$station_name',
            owner_name='$owner_name',
            email='$email',
            phone='$phone',
            district='$district',
            location='$location',
            latitude='$latitude',
            longitude='$longitude',
            charger_type='$charger_type',
            battery_rental='$battery_rental',
            username='$username',
            password='$password',
            image_path='$imageUpdate'
            WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Station updated successfully!'); window.location='station_profile.php';</script>";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Charging Station</title>
<style>
    body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(to right, #16a34a, #15803d);
        margin: 0;
        padding: 0;
        min-height: 100vh;
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
        text-align: center;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
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
    img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid #16a34a;
    }
    .error {
        color: red;
        font-size: 13px;
        text-align: left;
        margin-bottom: -5px;
    }
</style>
</head>
<body>

<h2 style="text-align:center; color:#fff; margin-top:30px;">Edit Charging Station</h2>

<div class="page-wrapper">
<div class="container">
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
       <img id="previewImg" src="<?php echo $row['image_path'] ? $row['image_path'] : 'uploads/default.png'; ?>" alt="Station Image">

        <label>Station Name</label>
        <input type="text" name="station_name" id="station_name" placeholder="Enter station name" value="<?php echo $row['station_name']; ?>" required>

        <label>Owner Name</label>
        <input type="text" name="owner_name" id="owner_name" placeholder="Owner name" value="<?php echo $row['owner_name']; ?>" required>

        <label>Email</label>
        <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $row['email']; ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" id="phone" placeholder="10-digit phone number" value="<?php echo $row['phone']; ?>" required>

        <label>District</label>
        <select name="district" id="district" required>
            <option value="">Select District</option>
            <?php
            $districts = ["Thiruvananthapuram","Kollam","Pathanamthitta","Alappuzha","Kottayam","Idukki","Ernakulam","Thrissur","Palakkad","Malappuram","Kozhikode","Wayanad","Kannur","Kasaragod"];
            foreach ($districts as $d) {
                $sel = ($row['district'] == $d) ? 'selected' : '';
                echo "<option value='$d' $sel>$d</option>";
            }
            ?>
        </select>

        <label>Location</label>
        <input type="text" name="location" id="location" placeholder="Enter location" value="<?php echo $row['location']; ?>" required>

        <label>Latitude</label>
        <input type="text" name="latitude" id="latitude" placeholder="Latitude" value="<?php echo $row['latitude']; ?>">

        <label>Longitude</label>
        <input type="text" name="longitude" id="longitude" placeholder="Longitude" value="<?php echo $row['longitude']; ?>">

        <label>Charger Type</label>
        <select name="charger_type" required>
            <option value="Normal" <?php if($row['charger_type']=="Normal") echo "selected"; ?>>Normal</option>
            <option value="Fast" <?php if($row['charger_type']=="Fast") echo "selected"; ?>>Fast</option>
        </select>

        <label>Battery Rental</label>
        <select name="battery_rental" required>
            <option value="Yes" <?php if($row['battery_rental']=="Yes") echo "selected"; ?>>Yes</option>
            <option value="No" <?php if($row['battery_rental']=="No") echo "selected"; ?>>No</option>
        </select>

        <label>Username</label>
        <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $row['username']; ?>" required>

        <label>Password</label>
        <input type="text" name="password" id="password" placeholder="Password" value="<?php echo $row['password']; ?>" required>

        <label>Station Image</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update">Update Station</button>
    </form>

    <p class="text-center mt-3">
     <a href="station_profile.php" style="color:#198754;font-weight:600;">Back to Profile</a>
  </p>
</div>
</div>

<script>
function validateForm() {
    let name = document.getElementById('station_name').value.trim();
    let owner = document.getElementById('owner_name').value.trim();
    let phone = document.getElementById('phone').value.trim();
    let pass = document.getElementById('password').value.trim();
    let district = document.getElementById('district').value.trim().toLowerCase();
    let location = document.getElementById('location').value.trim().toLowerCase();

    let nameRegex = /^[A-Za-z\s]+$/;
    let phoneRegex = /^[0-9]{10}$/;
    let passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

    if (!nameRegex.test(name)) { alert("Station name should contain letters and spaces only."); return false; }
    if (!nameRegex.test(owner)) { alert("Owner name should contain letters and spaces only."); return false; }
    if (!phoneRegex.test(phone)) { alert("Phone number must be 10 digits."); return false; }
    if (!passRegex.test(pass)) { alert("Password must contain uppercase, lowercase, number, and special character."); return false; }
    if (!location.includes(district)) { alert("Location must be within the selected district."); return false; }

    return true;
}
</script>
</body>
</html>
