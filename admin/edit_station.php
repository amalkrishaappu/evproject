<?php
include('../config/db.php');

$id = $_GET['id'];
$sql = "SELECT * FROM station WHERE id='$id'";
$result = mysqli_query($conn, $sql);
$station = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $slots = $_POST['slots'];
    $available_slots = $_POST['available_slots'];
    $sql = "UPDATE station SET name='$name', location='$location', slot='$slots', available_slot='$available_slots' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: stations.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Station</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 500px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Edit Station</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($station['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Location</label>
      <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($station['location']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Slots</label>
      <input type="number" class="form-control" name="slots" value="<?= htmlspecialchars($station['slot']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Available Slots</label>
      <input type="number" class="form-control" name="available_slots" value="<?= htmlspecialchars($station['available_slot']) ?>" required>
    </div>
    <div class="d-grid">
      <button type="submit" name="update" class="btn btn-primary btn-lg">Update Station</button>
    </div>
  </form>
</div>

</body>
</html>
