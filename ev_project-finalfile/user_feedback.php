<?php
include 'db.php';
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user name & email
$user = $conn->query("SELECT name, email FROM users WHERE id='$user_id'")->fetch_assoc();

// Check if user already submitted Feedback or Complaint
$check_feedback = $conn->query("SELECT COUNT(*) AS cnt FROM feedback WHERE user_id='$user_id' AND type='Feedback'")->fetch_assoc();
$check_complaint = $conn->query("SELECT COUNT(*) AS cnt FROM feedback WHERE user_id='$user_id' AND type='Complaint'")->fetch_assoc();

$feedback_given = $check_feedback['cnt'] > 0;
$complaint_given = $check_complaint['cnt'] > 0;

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $message = $_POST['message'];
    $rating = ($type === 'Feedback') ? $_POST['rating'] : 0;

    // Validation: allow only one of each type
    if (($type === 'Feedback' && $feedback_given) || ($type === 'Complaint' && $complaint_given)) {
        echo "<script>alert('❌ You have already submitted a $type. You cannot submit another.'); window.location='user_feedback.php';</script>";
        exit;
    }

    $sql = "INSERT INTO feedback (user_id, title, type, message, rating, date)
            VALUES ('$user_id', '$title', '$type', '$message', '$rating', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Thank you for your $type!'); window.location='user_feedback.php';</script>";
    } else {
        echo "<script>alert('❌ Error submitting. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback / Complaint</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
<style>
body {
  font-family:'Poppins',sans-serif;
  background:linear-gradient(120deg,#e8f5e9,#f9fff9);
  min-height:100vh;
  margin:0;
}
.navbar {
  background:#0f5132;
  color:white;
  padding:15px 25px;
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.navbar h1 {
  margin:0;
  font-size:20px;
}
.navbar a {
  color:white;
  text-decoration:none;
  margin-left:20px;
  font-weight:500;
}
.navbar a:hover { text-decoration:underline; }

.container {
  background:white;
  width:90%;
  max-width:500px;
  padding:25px;
  border-radius:16px;
  box-shadow:0 6px 25px rgba(0,0,0,0.08);
  margin:40px auto;
}
h2 {
  text-align:center;
  color:#14532d;
  margin-bottom:15px;
}
label {
  display:block;
  font-weight:500;
  margin-top:10px;
}
input, select, textarea {
  width:100%;
  padding:10px;
  border:1px solid #cfe9d2;
  border-radius:8px;
  margin-top:5px;
  font-size:14px;
  outline:none;
}
input:focus, select:focus, textarea:focus {
  border-color:#0f5132;
  box-shadow:0 0 4px #a7f3d0;
}
button {
  margin-top:20px;
  width:100%;
  padding:10px;
  background:linear-gradient(90deg,#14532d,#0f5132);
  border:none;
  border-radius:8px;
  color:white;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
}
button:hover { background:#166534; }

.info-box {
  background:#e9f7ef;
  padding:10px 15px;
  border-radius:8px;
  margin-bottom:15px;
  font-size:14px;
  color:#14532d;
}
.notice {
  background:#fff3cd;
  color:#856404;
  padding:10px;
  border-radius:8px;
  margin-bottom:10px;
}
</style>

<script>
function toggleRating() {
  const type = document.querySelector("select[name='type']").value;
  const ratingField = document.getElementById("ratingField");
  if (type === "Feedback") {
    ratingField.style.display = "block";
  } else {
    ratingField.style.display = "none";
  }
}
</script>
</head>
<body>

<div class="navbar">
  <h1>⚡ EcoCharge</h1>
  <div>
    <a href="user_home.php">Home</a>
    <a href="user_feedback.php">Feedback</a>
    <a href="user_logout.php">Logout</a>
  </div>
</div>

<div class="container">
  <h2>🗣️ Feedback & Complaints</h2>

  <div class="info-box">
    Logged in as <strong><?php echo htmlspecialchars($user['name']); ?></strong>  
    (<?php echo htmlspecialchars($user['email']); ?>)
  </div>

  <?php if ($feedback_given): ?>
    <div class="notice">✅ You already submitted a Feedback.</div>
  <?php endif; ?>
  <?php if ($complaint_given): ?>
    <div class="notice">⚠️ You already submitted a Complaint.</div>
  <?php endif; ?>

  <form method="POST">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Type</label>
    <select name="type" onchange="toggleRating()" required>
      <option value="">-- Select Type --</option>
      <option value="Feedback" <?php echo $feedback_given ? 'disabled' : ''; ?>>Feedback</option>
      <option value="Complaint" <?php echo $complaint_given ? 'disabled' : ''; ?>>Complaint</option>
    </select>

    <div id="ratingField" style="display:none;">
      <label>Rating (for Feedback only)</label>
      <select name="rating">
        <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
        <option value="4">⭐⭐⭐⭐ Good</option>
        <option value="3">⭐⭐⭐ Average</option>
        <option value="2">⭐⭐ Poor</option>
        <option value="1">⭐ Very Poor</option>
      </select>
    </div>

    <label>Message</label>
    <textarea name="message" rows="4" required></textarea>

    <button name="submit">Submit</button>
  </form>
</div>

<script>
toggleRating();
</script>

</body>
</html>
