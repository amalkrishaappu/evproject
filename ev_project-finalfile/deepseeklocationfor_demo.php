<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EV ChargePoint - Find Charging Stations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo i {
            font-size: 28px;
            color: #2ecc71;
        }
        
        .logo h1 {
            font-size: 24px;
            font-weight: 700;
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: #2ecc71;
        }
        
        .search-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .search-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .search-header h2 {
            font-size: 22px;
            color: #2c3e50;
        }
        
        .filters {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .filter-item label {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .filter-item select, .filter-item input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
        }
        
        .search-button {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .search-button:hover {
            background: #27ae60;
        }
        
        .map-container {
            display: flex;
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .map {
            flex: 7;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            height: 500px;
        }
        
        .map-placeholder {
            width: 100%;
            height: 100%;
            background: #eef2f7;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #7f8c8d;
        }
        
        .map-placeholder i {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .stations-list {
            flex: 3;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            overflow-y: auto;
            max-height: 500px;
        }
        
        .stations-list h3 {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            color: #2c3e50;
        }
        
        .station-item {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #f8f9fa;
            transition: transform 0.3s;
        }
        
        .station-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .station-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .station-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .station-distance {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .station-details {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .station-detail {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .station-detail i {
            color: #3498db;
        }
        
        .station-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-button {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-reserve {
            background: #3498db;
            color: white;
            border: none;
        }
        
        .btn-reserve:hover {
            background: #2980b9;
        }
        
        .btn-directions {
            background: transparent;
            border: 1px solid #3498db;
            color: #3498db;
        }
        
        .btn-directions:hover {
            background: #eaf2f8;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }
        
        .feature-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        
        .feature-icon {
            font-size: 40px;
            color: #2ecc71;
            margin-bottom: 15px;
        }
        
        .feature-card h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .feature-card p {
            color: #7f8c8d;
        }
        
        footer {
            background: #2c3e50;
            color: white;
            padding: 30px 0;
            margin-top: 40px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .footer-section {
            flex: 1;
            min-width: 250px;
        }
        
        .footer-section h3 {
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .footer-section p, .footer-section a {
            color: #ecf0f1;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
        }
        
        .footer-section a:hover {
            color: #2ecc71;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-icons a {
            font-size: 20px;
        }
        
        .copyright {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #3c546e;
        }
        
        @media (max-width: 900px) {
            .map-container {
                flex-direction: column;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            nav ul {
                justify-content: center;
            }
        }
        
        @media (max-width: 600px) {
            .filters {
                flex-direction: column;
            }
            
            .search-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-charging-station"></i>
                    <h1>EV ChargePoint</h1>
                </div>
                <nav>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Map</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <section class="search-section">
            <div class="search-header">
                <h2>Find EV Charging Stations & Battery Rental Points</h2>
                <button class="search-button">
                    <i class="fas fa-location-arrow"></i> Use My Location
                </button>
            </div>
            
            <div class="filters">
                <div class="filter-item">
                    <label for="charger-type">Charger Type</label>
                    <select id="charger-type">
                        <option value="all">All Types</option>
                        <option value="level2">Level 2 (240V)</option>
                        <option value="dc-fast">DC Fast Charging</option>
                        <option value="tesla">Tesla Supercharger</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label for="connector">Connector Type</label>
                    <select id="connector">
                        <option value="all">All Connectors</option>
                        <option value="ccs">CCS (Combo)</option>
                        <option value="chademo">CHAdeMO</option>
                        <option value="type2">Type 2 (Mennekes)</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label for="battery-rental">Battery Rental</label>
                    <select id="battery-rental">
                        <option value="all">All Stations</option>
                        <option value="yes">With Battery Rental</option>
                        <option value="no">Without Battery Rental</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label for="max-distance">Max Distance (km)</label>
                    <input type="range" id="max-distance" min="1" max="50" value="10">
                    <span id="distance-value">10 km</span>
                </div>
            </div>
        </section>
        
        <div class="map-container">
            <div class="map">
                <div class="map-placeholder">
                    <i class="fas fa-map-marked-alt"></i>
                    <p>Interactive map loading...</p>
                    <p>Map would show your location and nearby charging stations</p>
                </div>
            </div>
            
            <div class="stations-list">
                <h3>Nearby Charging Stations</h3>
                
                <?php
                // Sample PHP code to generate station listings
                $stations = array(
                    array(
                        "name" => "Downtown Charging Hub",
                        "distance" => "1.2 km",
                        "available" => 4,
                        "total" => 8,
                        "battery_rental" => true,
                        "types" => "Level 2, DC Fast",
                        "price" => "$0.25/kWh"
                    ),
                    array(
                        "name" => "Green Energy Station",
                        "distance" => "2.5 km",
                        "available" => 2,
                        "total" => 4,
                        "battery_rental" => true,
                        "types" => "Level 2",
                        "price" => "$0.22/kWh"
                    ),
                    array(
                        "name" => "Tech Park Charging",
                        "distance" => "3.8 km",
                        "available" => 6,
                        "total" => 10,
                        "battery_rental" => false,
                        "types" => "DC Fast, Tesla",
                        "price" => "$0.28/kWh"
                    ),
                    array(
                        "name" => "Mall of Electronics",
                        "distance" => "4.2 km",
                        "available" => 3,
                        "total" => 6,
                        "battery_rental" => true,
                        "types" => "Level 2",
                        "price" => "$0.24/kWh"
                    ),
                    array(
                        "name" => "Highway Rest Stop",
                        "distance" => "5.7 km",
                        "available" => 8,
                        "total" => 12,
                        "battery_rental" => false,
                        "types" => "DC Fast, Tesla",
                        "price" => "$0.30/kWh"
                    )
                );
                
                foreach ($stations as $station) {
                    echo '<div class="station-item">';
                    echo '<div class="station-header">';
                    echo '<span class="station-name">' . $station["name"] . '</span>';
                    echo '<span class="station-distance">' . $station["distance"] . '</span>';
                    echo '</div>';
                    
                    echo '<div class="station-details">';
                    echo '<div class="station-detail"><i class="fas fa-plug"></i> ' . $station["types"] . '</div>';
                    echo '<div class="station-detail"><i class="fas fa-battery-three-quarters"></i> ' . $station["available"] . '/' . $station["total"] . ' Available</div>';
                    echo '</div>';
                    
                    echo '<div class="station-details">';
                    if ($station["battery_rental"]) {
                        echo '<div class="station-detail"><i class="fas fa-exchange-alt"></i> Battery Rental Available</div>';
                    } else {
                        echo '<div class="station-detail"><i class="fas fa-exchange-alt"></i> No Battery Rental</div>';
                    }
                    echo '<div class="station-detail"><i class="fas fa-tag"></i> ' . $station["price"] . '</div>';
                    echo '</div>';
                    
                    echo '<div class="station-actions">';
                    echo '<button class="action-button btn-reserve"><i class="fas fa-calendar-check"></i> Reserve</button>';
                    echo '<button class="action-button btn-directions"><i class="fas fa-route"></i> Directions</button>';
                    echo '</div>';
                    
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Fast Charging</h3>
                <p>Our stations offer DC fast charging that can charge your EV to 80% in under 30 minutes.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-battery-full"></i>
                </div>
                <h3>Battery Rental</h3>
                <p>Swap your depleted battery for a fully charged one in minutes and get back on the road.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-solar-panel"></i>
                </div>
                <h3>Solar Powered</h3>
                <p>Many of our stations are powered by renewable energy sources for sustainable charging.</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>EV ChargePoint</h3>
                    <p>Providing reliable and accessible EV charging solutions with integrated battery rental services to accelerate the transition to sustainable transportation.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="#">Find a Station</a>
                    <a href="#">Pricing Plans</a>
                    <a href="#">Battery Rental</a>
                    <a href="#">Business Solutions</a>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Green Energy Blvd, Eco City</p>
                    <p><i class="fas fa-phone"></i> (555) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> info@evchargepoint.com</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 EV ChargePoint. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // JavaScript for the distance slider
        const distanceSlider = document.getElementById('max-distance');
        const distanceValue = document.getElementById('distance-value');
        
        distanceSlider.addEventListener('input', function() {
            distanceValue.textContent = this.value + ' km';
        });
        
        // Simulate loading animation for the map
        setTimeout(function() {
            document.querySelector('.map-placeholder').innerHTML = `
                <div style="text-align: center; padding: 20px;">
                    <i class="fas fa-map-marker-alt" style="font-size: 48px; color: #3498db; margin-bottom: 15px;"></i>
                    <p>Map would display here with real station locations</p>
                    <p style="margin-top: 20px; font-size: 14px;">In a real implementation, this would show an interactive map from Google Maps or similar service with markers for each charging station.</p>
                </div>
            `;
        }, 2000);
        
        // Use My Location button functionality
        document.querySelector('.search-button').addEventListener('click', function() {
            alert('In a real implementation, this would get your current location and show nearby stations.');
        });
    </script>
</body>
</html>