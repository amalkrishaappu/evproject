<?php
include '../config/db.php';
session_start();

// Uncomment this if needed
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }

$station_id = null;
$station = null;

// Check if a station ID was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['station_id'])) {
    $station_id = $_POST['station_id'];

    // Fetch station info (total and available slots)
    $station_query = mysqli_query($conn, "SELECT name, slot, available_slot FROM station WHERE id = '$station_id'");
    $station = mysqli_fetch_assoc($station_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Station Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4CAF50; /* Green */
            --secondary-color: #FFC107; /* Amber */
            --background-color: #f4f7f6;
            --card-background: #ffffff;
            --text-color: #333;
            --light-text-color: #666;
            --border-color: #ddd;
            --error-color: #f44336; /* Red */
            --success-color: #8BC34A; /* Light Green */
            --shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background-color: var(--card-background);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            max-width: 600px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
            font-size: 1.8em;
        }

        h3 {
            color: var(--text-color);
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: 500;
            font-size: 1.4em;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 8px;
        }

        p {
            margin-bottom: 10px;
            font-size: 1.1em;
            color: var(--light-text-color);
        }

        p strong {
            color: var(--text-color);
        }

        /* --- Alerts and Messages --- */
        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            font-size: 1.1em;
        }

        .message.error {
            background-color: #ffebee;
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .message.success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .success-text {
            color: var(--success-color);
        }

        .error-text {
            color: var(--error-color);
        }

        /* --- Form Styling --- */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        form label {
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--light-text-color);
        }

        form input[type="text"],
        form input[type="date"] {
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1em;
            width: calc(100% - 24px); /* Account for padding */
            box-sizing: border-box;
            /* -webkit-appearance: none; */
        }

        form button {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form button:hover {
            background-color: #43A047;
        }

        /* --- List Styling for Booked Slots --- */
        ul {
            list-style: none;
            padding: 0;
            margin-top: 15px;
        }

        ul li {
            background-color: #fffde7;
            border: 1px solid var(--secondary-color);
            padding: 10px 15px;
            margin-bottom: 8px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1em;
            color: var(--light-text-color);
        }

        /* --- Links --- */
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-weight: bold;
            font-size: 1em;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        a:hover {
            background-color: #43A047;
            transform: translateY(-2px);
        }

        /* Back link specific styling */
        a.back-link {
            background-color: #607D8B;
            margin-top: 30px;
        }

        a.back-link:hover {
            background-color: #546E7A;
        }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .container {
                padding: 20px;
                border-radius: 8px;
            }

            h2 {
                font-size: 1.6em;
            }

            h3 {
                font-size: 1.2em;
            }

            form button, a {
                padding: 12px;
                font-size: 0.95em;
            }

            form input[type="text"],
            form input[type="date"] {
                padding: 10px;
                width: 100%;
            }

            ul li {
                padding: 8px 12px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 15px;
            }

            h2 {
                font-size: 1.4em;
            }

            h3 {
                font-size: 1.1em;
            }

            form {
                gap: 10px;
            }

            form button, a {
                padding: 10px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (!$station_id || !$station) {
            // Display form to select station ID if not yet selected
            echo "<h2>Select Station</h2>";
            echo "<form method='POST'>";
            echo "<label for='station-id'>Enter Station ID:</label>";
            echo "<input type='text' id='station-id' name='station_id' required placeholder='e.g., 1, 2, 3'>";
            echo "<button type='submit'>View Station</button>";
            echo "</form>";
            echo "<a href='stations.php' class='back-link'>⬅️ Back to Stations</a>"; // Still provide a way back
        } else {
            // Station is found, proceed with details and date selection
            echo "<h2><span class='icon'>&#x1F50B;</span> Station: " . htmlspecialchars($station['name']) . "</h2>";
            echo "<p>Total Slots: <strong>{$station['slot']}</strong></p>";
            echo "<p>Live Available Slots: <strong>{$station['available_slot']}</strong></p>";

            // Check if a date was submitted for booking details
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
                $date = $_POST['date'];

                // Get all bookings for this station and date
                $sql = "SELECT booking_time FROM booking WHERE station_id='$station_id' AND booking_date='$date'";
                $result = mysqli_query($conn, $sql);

                $booked_slots = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $booked_slots[] = $row['booking_time'];
                }

                $booked_count = count($booked_slots);
                $available_count = $station['slot'] - $booked_count;

                echo "<h3>📅 Bookings for <strong>$date</strong>:</h3>";

                if ($booked_count > 0) {
                    echo "<ul>";
                    foreach ($booked_slots as $time) {
                        echo "<li>⛔ $time</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<div class='message success'>✅ No bookings yet for this date.</div>";
                }

                echo "<p><strong>Booked:</strong> $booked_count / {$station['slot']}</p>";
                echo "<p><strong>Available:</strong> <span class='" . ($available_count > 0 ? 'success-text' : 'error-text') . "'>$available_count</span> slots</p>";

                echo "<a href='booking.php?id=$station_id&date=$date'>➡️ Book a Slot</a>";
                echo "<br><br>"; // Add some space
                echo "<form method='POST'>"; // Form to check another date for the SAME station
                echo "<input type='hidden' name='station_id' value='" . htmlspecialchars($station_id) . "'>";
                echo "<label for='booking-date'>📅 Select Another Date:</label>";
                echo "<input type='date' id='booking-date' name='date' required>";
                echo "<button type='submit'>Check Slots</button>";
                echo "</form>";

            } else {
                // Display form to select date if station ID is valid but no date is selected
                echo "<h3>Check Booked Slots</h3>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='station_id' value='" . htmlspecialchars($station_id) . "'>"; // Keep station_id for next POST
                echo "<label for='booking-date'>📅 Select Date:</label>";
                echo "<input type='date' id='booking-date' name='date' required>";
                echo "<button type='submit'>Check Slots</button>";
                echo "</form>";
            }
            echo "<a href='stations.php' class='back-link'>⬅️ Back to Stations</a>"; // Back to stations link
        }
        ?>
    </div>
</body>
</html>