<?php
include('db.php');
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$userId'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body {
    background: linear-gradient(to bottom right, #e8f5e9, #a5d6a7);
    font-family: 'Segoe UI', sans-serif;
}
.card {
    background: white;
    border-top: 6px solid #2e7d32;
    align-items: center;
}
label {
    font-weight: 600;
    color: #1b5e20;
}
button {
    background-color: #2e7d32;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
button:hover {
    background-color: #256328;
    transform: scale(1.05);
}
.modal-content {
    background: white;
    padding: 25px;
    border-radius: 12px;
    width: 360px;
    text-align: center;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
}
.modal-content input {
    width: 100%;
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
.profile-img {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #2e7d32;
    margin-bottom: 15px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
</style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

<div class="card p-8 rounded-2xl shadow-2xl w-full max-w-md text-center space-y-3">
    <!-- Profile Image -->
    <?php if (!empty($user['image'])): ?>
        <img src="<?php echo htmlspecialchars($user['image']); ?>" class="profile-img" alt="Profile Image">
    <?php else: ?>
        <img src="images/default.png" class="profile-img" alt="Default Profile Image">
    <?php endif; ?>

    <h2 class="text-2xl font-bold text-[#1b5e20]">Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>

    <div class="text-center mt-4 space-y-2">
        <p><span class="font-semibold text-[#1b5e20]">Username:</span> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><span class="font-semibold text-[#1b5e20]">Age:</span> <?php echo htmlspecialchars($user['age']); ?></p>
        <p><span class="font-semibold text-[#1b5e20]">Email:</span> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><span class="font-semibold text-[#1b5e20]">Phone:</span> <?php echo htmlspecialchars($user['phoneno']); ?></p>
        <p><span class="font-semibold text-[#1b5e20]">Address:</span> <?php echo htmlspecialchars($user['address']); ?></p>
    </div>

    <div class="mt-6 flex flex-col gap-3 items-center">
        <button type="button" onclick="openModal()">✏️ Edit Profile</button>
        <a href="user_home.php" class="text-green-700 font-semibold hover:underline">🏠 Back to Home</a>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content relative">
        <h3 class="text-xl font-semibold text-[#1b5e20] mb-3">Edit Profile</h3>
        <form method="POST" action="user_profile_update.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Age</label>
            <input type="number" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" required min="18">

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Phone</label>
            <input type="text" name="phoneno" value="<?php echo htmlspecialchars($user['phoneno']); ?>" required pattern="[0-9]{10}">

            <label>Address</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter new password" required>

            <label>Profile Image</label>
            <input type="file" name="image" accept="image/*">

            <div class="flex justify-between mt-4">
                <button type="submit">✅ Update</button>
                <button type="button" onclick="closeModal()">❌ Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('editModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Validation
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const age = parseInt(document.getElementById('age').value);
    const phone = document.getElementById('phoneno').value.trim();
    const password = document.getElementById('password').value.trim();

    const nameRegex = /^[A-Za-z\s]+$/;
    if (!nameRegex.test(name)) {
        alert("Name should contain only letters and spaces.");
        return false;
    }

    if (age < 18) {
        alert("Age must be 18 or above.");
        return false;
    }

    const phoneRegex = /^[0-9]{10}$/;
    if (!phoneRegex.test(phone)) {
        alert("Phone number must be exactly 10 digits.");
        return false;
    }

    const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
    if (!passRegex.test(password)) {
        alert("Password must have at least 8 characters, including uppercase, lowercase, number, and special character.");
        return false;
    }

    return true;
}
</script>

</body>
</html>

<?php $conn->close(); ?>


