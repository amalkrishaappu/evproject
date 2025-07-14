<?php
include '../config/db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? 1; // fallback user

if (isset($_POST['rent'])) {
    $station_id = $_POST['station_id'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];

    $query = "SELECT * FROM battery WHERE brand='$brand' AND model='$model' AND is_rented=0 AND station_id='$station_id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($battery = mysqli_fetch_assoc($result)) {
        $battery_id = $battery['battery_id'];

        mysqli_query($conn, "UPDATE battery SET is_rented = 1 WHERE battery_id = '$battery_id'");
        mysqli_query($conn, "INSERT INTO battery_rental (battery_id, user_id, station_id) VALUES ('$battery_id', '$user_id', '$station_id')");

        $rental_id = mysqli_insert_id($conn);
        $msg = "Battery Rented: ID $battery_id. Your Rental ID is <strong>$rental_id</strong>. Use this ID to return the battery.";
    } else {
        $msg = "No available battery for selected brand and model at this station.";
    }
}
?>
<h2>Rent Battery</h2>
<?= $msg ?? '' ?>
<form method="POST">
    Station:
    <select name="station_id">
        <?php
        $stations = mysqli_query($conn, "SELECT * FROM station");
        while ($row = mysqli_fetch_assoc($stations)) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
    </select><br>
    Brand:
    <select name="brand">
        <?php
        $brands = mysqli_query($conn, "SELECT DISTINCT brand FROM battery");
        while ($row = mysqli_fetch_assoc($brands)) {
            echo "<option value='{$row['brand']}'>{$row['brand']}</option>";
        }
        ?>
    </select><br>
    Model:
    <select name="model">
        <?php
        $models = mysqli_query($conn, "SELECT DISTINCT model FROM battery");
        while ($row = mysqli_fetch_assoc($models)) {
            echo "<option value='{$row['model']}'>{$row['model']}</option>";
        }
        ?>
    </select><br>
    <button name="rent">Rent Battery</button>
</form>