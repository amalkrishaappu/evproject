<?php
include("db.php");
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$query = "
    SELECT u.name AS user_name, u.email, l.login_time, l.logout_time
    FROM user_logins l
    JOIN users u ON l.user_id = u.id
    ORDER BY l.login_time DESC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login & Logout History</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
            margin: 0;
            padding: 0;
            color: #2e7d32;
        }

        h2 {
            text-align: center;
            margin-top: 40px;
            font-size: 28px;
            color: #1b5e20;
            text-shadow: 1px 1px 2px #a5d6a7;
        }

        table {
            width: 85%;
            margin: 40px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(46, 125, 50, 0.2);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            text-align: center;
            border-bottom: 1px solid #c8e6c9;
        }

        th {
            background-color: #388e3c;
            color: white;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f1f8e9;
        }

        td {
            color: #2e7d32;
        }

        .status-active {
            color: #388e3c;
            font-weight: bold;
        }

        .status-logout {
            color: #616161;
            font-weight: bold;
        }

        .back-btn {
            display: inline-block;
            background-color: #2e7d32;
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            margin: 30px auto;
            transition: 0.3s;
        }

        .back-btn:hover {
            background-color: #1b5e20;
            box-shadow: 0px 4px 10px rgba(27, 94, 32, 0.3);
        }

        .footer {
            text-align: center;
            color: #81c784;
            font-size: 14px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            table {
                width: 95%;
            }
            th, td {
                padding: 10px;
                font-size: 14px;
            }
            h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<h2>🕒 User Login & Logout History</h2>

<table>
    <tr>
        <th>User Name</th>
        <th>Email</th>
        <th>Login Time</th>
        <th>Logout Time</th>
        <th>Status</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['login_time']) ?></td>
            <td><?= $row['logout_time'] ? htmlspecialchars($row['logout_time']) : '---' ?></td>
            <td>
                <?php if ($row['logout_time']) { ?>
                    <span class="status-logout">✅ Logged Out</span>
                <?php } else { ?>
                    <span class="status-active">🟢 Active</span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<div style="text-align:center;">
    <a href="admin_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>

<div class="footer">
    © <?= date("Y") ?> Gym Management System | User Login Report
</div>

</body>
</html>
