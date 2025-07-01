<?php
include('../config/db.php');


if (isset($_POST['add'])) {
    $id=$_POST['id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $slots = $_POST['slots'];
    $av_slots=$_POST['available'];
    $sql="insert into station(id,name,location,slot,available_slot)values('$id','$name','$location','$slots','$av_slots')";
    mysqli_query($conn,$sql);
    header("Location: stations.php");
}
?>

<h2>Add Charging Station</h2>
<form method="POST">
    id:<input type="text" name="id" required placeholder="id"><br>
    Name: <input type="text" name="name" required placeholder="name"><br>
    Location: <input type="text" name="location" required placeholder="location"><br>
    Slots: <input type="number" name="slots" required placeholder="slots"><br>
    available slots:<input type="number" name="available" required placeholder="avilable slotes"><br>
    <button type="submit" name="add">Add Station</button>
</form>

<!-- <?php include('../templates/admin/footer.php'); ?> -->
