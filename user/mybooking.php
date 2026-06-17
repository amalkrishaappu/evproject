<?php 
include '../config/db.php'; 
session_start(); 

if (!isset($_SESSION['user_id'])) {     
    header("Location: login.php");     
    exit; 
}  

$user_id = $_SESSION['user_id'];  

$sql = "SELECT b.id, s.name as station_name, b.booking_date, b.booking_time, b.status          
        FROM booking b JOIN station s ON b.station_id = s.id          
        WHERE b.user_id='$user_id'";  

$result = mysqli_query($conn, $sql); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .header h2 {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        .bookings-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .bookings-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bookings-table th {
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            color: white;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bookings-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #e8e8e8;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .bookings-table tbody tr {
            transition: all 0.3s ease;
        }

        .bookings-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .bookings-table tbody tr:last-child td {
            border-bottom: none;
        }

        .status {
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status.confirmed {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .status.pending {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
        }

        .status.cancelled {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .station-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .booking-date {
            color: #7f8c8d;
            font-weight: 500;
        }

        .booking-time {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            padding: 5px 12px;
            border-radius: 15px;
            color: #1976d2;
            font-weight: 600;
            border: 1px solid #2196f3;
        }

        .back-button {
            margin: 30px;
            text-align: center;
        }

        .back-button a {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-button a:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .no-bookings {
            text-align: center;
            padding: 60px 30px;
            color: #7f8c8d;
        }

        .no-bookings-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-bookings h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #5a6c7d;
        }

        .no-bookings p {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header h2 {
                font-size: 2rem;
            }

            .header p {
                font-size: 1rem;
            }

            .header {
                padding: 20px;
            }

            .table-container {
                padding: 15px;
            }

            .bookings-table {
                font-size: 0.9rem;
            }

            .bookings-table th,
            .bookings-table td {
                padding: 12px 8px;
            }

            .bookings-table th {
                font-size: 0.9rem;
            }

            .back-button {
                margin: 20px;
            }

            .back-button a {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .header h2 {
                font-size: 1.7rem;
            }

            .bookings-table th,
            .bookings-table td {
                padding: 10px 6px;
                font-size: 0.8rem;
            }

            .status {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .booking-time {
                padding: 4px 8px;
                font-size: 0.8rem;
            }

            .back-button a {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
        }

        /* Animation for page load */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            animation: fadeInUp 0.8s ease-out;
        }

        .bookings-table tbody tr {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .bookings-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .bookings-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .bookings-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .bookings-table tbody tr:nth-child(4) { animation-delay: 0.4s; }
        .bookings-table tbody tr:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>My Bookings</h2>
            <p>Manage and view your station bookings</p>
        </div>

        <div class="table-container">
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Station</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><span class="station-name"><?= htmlspecialchars($row['station_name']) ?></span></td>
                            <td><span class="booking-date"><?= date('M d, Y', strtotime($row['booking_date'])) ?></span></td>
                            <td><span class="booking-time"><?= date('h:i A', strtotime($row['booking_time'])) ?></span></td>
                            <td>
                                <span class="status <?= strtolower($row['status']) ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="no-bookings">
                    <div class="no-bookings-icon">📅</div>
                    <h3>No Bookings Found</h3>
                    <p>You haven't made any bookings yet. Start by browsing available stations.</p>
                </div>
            <?php } ?>
        </div>

        <div class="back-button">
            <a href="stations.php">← Back to Stations</a>
        </div>
    </div>
</body>
</html>