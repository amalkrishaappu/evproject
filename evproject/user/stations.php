<?php 
include '../config/db.php'; 
// session_start(); 
// if (!isset($_SESSION['user_id'])) { 
//     header("Location: login.php"); 
//     exit; 
// }  

// Fetch stations 
$sql = "select * from station"; 
$result = mysqli_query($conn, $sql); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charging Stations</title>
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
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .charging-type {
    color: #2980b9;
    font-weight: 600;
    margin-top: 5px;    
        }


        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        .header h2 {
            font-size: 3rem;
            font-weight: 300;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        .header .subtitle {
            opacity: 0.9;
            font-size: 1.2rem;
            position: relative;
            z-index: 1;
        }

        .charging-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            display: block;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px 30px;
        }

        .stations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .station-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #e8e8e8;
            position: relative;
            overflow: hidden;
        }

        .station-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .station-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border-color: #4facfe;
        }

        .station-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .station-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
        }

        .station-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .station-location {
            color: #7f8c8d;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .location-icon {
            margin-right: 8px;
            color: #e74c3c;
        }

        .station-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 25px 0;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border-radius: 15px;
            border: 1px solid #e3ebff;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .availability-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            padding: 15px;
            border-radius: 15px;
            font-weight: 600;
        }

        .availability-high {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .availability-medium {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .availability-low {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .view-slots-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .view-slots-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .logout-container {
            text-align: center;
            padding: 30px;
            border-top: 1px solid #e8e8e8;
        }

        .logout-btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .logout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 107, 107, 0.4);
            background: linear-gradient(135deg, #ee5a52 0%, #ff6b6b 100%);
        }

        .no-stations {
            text-align: center;
            padding: 80px 30px;
            color: #7f8c8d;
        }

        .no-stations-icon {
            font-size: 5rem;
            margin-bottom: 30px;
            opacity: 0.5;
        }

        .no-stations h3 {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #5a6c7d;
        }

        .no-stations p {
            font-size: 1.2rem;
            line-height: 1.6;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

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

        .station-card {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .station-card:nth-child(1) { animation-delay: 0.1s; }
        .station-card:nth-child(2) { animation-delay: 0.2s; }
        .station-card:nth-child(3) { animation-delay: 0.3s; }
        .station-card:nth-child(4) { animation-delay: 0.4s; }
        .station-card:nth-child(5) { animation-delay: 0.5s; }
        .station-card:nth-child(6) { animation-delay: 0.6s; }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .stations-grid {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h2 {
                font-size: 2.2rem;
            }

            .charging-icon {
                font-size: 3rem;
            }

            .content {
                padding: 20px;
            }

            .stations-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .station-card {
                padding: 20px;
            }

            .station-stats {
                gap: 15px;
            }

            .stat-item {
                padding: 15px;
            }

            .stat-number {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .header h2 {
                font-size: 1.8rem;
            }

            .header .subtitle {
                font-size: 1rem;
            }

            .charging-icon {
                font-size: 2.5rem;
            }

            .station-card {
                padding: 15px;
            }

            .station-header {
                flex-direction: column;
                text-align: center;
                margin-bottom: 15px;
            }

            .station-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .station-name {
                font-size: 1.3rem;
            }

            .station-stats {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .view-slots-btn {
                padding: 12px;
                font-size: 1rem;
            }

            .logout-btn {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="charging-icon">⚡</span>
            <h2>Nearby Charging Stations</h2>
            <p class="subtitle">Find and book your perfect charging spot</p>
        </div>

        <div class="content">
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <div class="stations-grid">
                    <?php while($row = mysqli_fetch_assoc($result)) { 
                        $availability_percentage = ($row['available_slot'] / $row['slot']) * 100;
                        $availability_class = '';
                        $availability_text = '';
                        
                        if ($availability_percentage >= 70) {
                            $availability_class = 'availability-high';
                            $availability_text = 'High Availability';
                        } elseif ($availability_percentage >= 30) {
                            $availability_class = 'availability-medium';
                            $availability_text = 'Medium Availability';
                        } else {
                            $availability_class = 'availability-low';
                            $availability_text = 'Limited Availability';
                        }
                    ?>
                    <div class="station-card">
                        <div class="station-header">
                            <div class="station-icon">🔌</div>
                            <div>
                                <div class="station-name"><?= htmlspecialchars($row['name']) ?></div>
                                <div class="station-location">
                                    <span class="location-icon">📍</span>
                                    <?= htmlspecialchars($row['location']) ?>
                                </div>
                            </div>
                        </div>
                       
                        <div class="station-location charging-type">
                            <span class="location-icon">⚙️</span>
                            Charging Type: <?= htmlspecialchars($row['charging_type']) ?>
                        </div>

                        <div class="station-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?= $row['slot'] ?></span>
                                <span class="stat-label">Total Slots</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?= $row['available_slot'] ?></span>
                                <span class="stat-label">Available</span>
                            </div>
                        </div>

                        <div class="availability-indicator <?= $availability_class ?>">
                            <?= $availability_text ?>
                        </div>

                        <a href="slots.php?id=<?= $row['id'] ?>" class="view-slots-btn">
                            View Available Slots
                        </a>
                    </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="no-stations">
                    <div class="no-stations-icon">🔋</div>
                    <h3>No Charging Stations Found</h3>
                    <p>We're working on adding more charging stations in your area. Please check back soon!</p>
                </div>
            <?php } ?>
        </div>

        <div class="logout-container">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>