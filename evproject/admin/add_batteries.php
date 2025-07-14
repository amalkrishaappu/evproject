<?php
include '../config/db.php';

if (isset($_POST['add'])) {
    // Collect data
    // $id = $_POST['battery_id'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $name=$_POST['name'];
    $capacity = $_POST['capacity'];
    $range=$_POST['range'];
    $dimension = $_POST['dimension'];
    $torque = $_POST['torque'];
    $life = $_POST['life'];
    $condition = $_POST['condition'];
    $warrenty=$_POST['warrenty'];
    $station_id = $_POST['station_id'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $image_path = '../uploads/' . basename($image);

    if (move_uploaded_file($tmp, $image_path)) {
        // Insert into DB
        $sql = "INSERT INTO battery (brand, model,name,capacity,range,dimension, torque, life_remaining, condition, warrenty,station_id, image)
                VALUES ('$id', '$brand', '$model', '$name','$capacity','$range','$dimension', '$torque', '$life', '$condition','$warrenty','$station_id', '$image')";
        if (mysqli_query($conn, $sql)) {
            echo "✅ Battery added successfully!";
        } else {
            echo "❌ Error: " . mysqli_error($conn);
        }
    } else {
        echo "❌ Failed to upload image.";
    }
}

?>
<form method="POST" enctype="multipart/form-data">
    <!-- ID: <input name="battery_id" required><br> -->
    Brand: <input name="brand" required><br>
    Model: <input name="model" required><br>
    name:<input name="name" required><br>
    Capacity: <input name="capacity" required><br>
    range:<input name="range" required><br>
    Dimension: <input name="dimension" required><br>
    Torque: <input name="torque" required><br>
    Life Remaining: <input type="number" name="life" min="0" max="100" required><br>
    Condition:
    <select name="condition">
        <option>Good</option>
        <option>Moderate</option>
        <option>Poor</option>
    </select><br>
    warrenty:<input name="warrenty" required><br>
    Station:
    <select name="station_id">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM station");
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
    </select><br>
    Image: <input type="file" name="image" accept="image/*" required><br><br>
    <button name="add">Add Battery</button>
</form>
