<?php
include '../config/db.php';

// We will only process the form if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {

    // --- 1. Collect and Validate Form Data ---
    // Check if all required fields are present
    $required_fields = ['brand', 'model', 'capacity', 'range', 'torque', 'life', 'condition', 'station_id'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            die("❌ Error: The '{$field}' field is required. Please go back and fill it out.");
        }
    }

    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $capacity = $_POST['capacity'];
    $range = $_POST['range'];
    $torque = $_POST['torque'];
    $life = $_POST['life'];
    $condition = $_POST['condition'];
    $station_id = $_POST['station_id'];

    // --- 2. Handle Image Upload ---
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        
        $targetDir = "../battery/"; // The folder to save images in
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetPath = $targetDir . $imageName;
        $tmp_path = $_FILES['image']['tmp_name'];

        // Create the directory if it doesn't already exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // --- 3. Insert into Database Securely ---
        
        // SQL query with exactly 9 columns and 9 placeholders (?)
        // IMPORTANT: Make sure your table's station column is named `st_id`. If it's `station_id`, change it below.
        $sql = "INSERT INTO battery (brand, model, capacity, `range`, torque, `condition`, life_remaining, st_id, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            // Bind the 9 variables to the 9 placeholders.
            // The type string "ssssssiss" has 9 characters.
            // s = string, i = integer
            mysqli_stmt_bind_param($stmt, "ssssssiss", 
                $brand, 
                $model, 
                $capacity, 
                $range, 
                $torque, 
                $condition, 
                $life,
                $station_id,
                $imageName
            );

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // If the database insert succeeds, then move the file
                if (move_uploaded_file($tmp_path, $targetPath)) {
                    echo "✅ Battery added successfully!";
                } else {
                    echo "⚠️ Database record created, but the image file failed to upload.";
                }
            } else {
                echo "❌ Error executing statement: " . mysqli_stmt_error($stmt);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "❌ Error preparing statement: " . mysqli_error($conn);
        }
    } else {
        echo "❌ No image was uploaded or there was an upload error. Error code: " . $_FILES['image']['error'];
    }
}
?>

<!-- Your HTML form does not need any changes -->
<form method="POST" enctype="multipart/form-data">
    Brand: <input name="brand" required><br>
    Model: <input name="model" required><br>
    Capacity: <input name="capacity" required><br>
    range:<input name="range" required><br>
    Torque: <input name="torque" required><br>
    Life Remaining: <input type="number" name="life" min="0" max="100" required><br>
    Condition:
    <select name="condition">
        <option>Good</option>
        <option>Moderate</option>
        <option>Poor</option>
    </select><br>
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