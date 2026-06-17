<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoCharge - EV Charging & Battery Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>

        .gradient-button {
            background: linear-gradient(to right, #8fbe93ff, #abd0a5ff); /* Blue gradient */
            transition: background 0.3s ease;
        }
        .gradient-button:hover {
            background: linear-gradient(to right, #aedab3ff, #90c395ff);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #8cc48eff 0%, #e2f7cbff 100%);
        }
        .map-container {
            height: 400px;
            background-color: #e5e7eb;
        }
        .charging-station-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .battery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .slider {
        position: relative;
        width: 100%;
        max-width: 900px;
        height: 500px;
        margin: 50px auto;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }

        .slides {
        display: flex;
        width: 300%;
        height: 100%;
        animation: slideFade 12s infinite;
        }

        .slides img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        }

        @keyframes slideFade {
        0%    { transform: translateX(0);    opacity: 1; }
        25%   { transform: translateX(0);    opacity: 1; }
        33%   { transform: translateX(-100%); opacity: 0.7; }
        58%   { transform: translateX(-100%); opacity: 1; }
        66%   { transform: translateX(-200%); opacity: 0.7; }
        91%   { transform: translateX(-200%); opacity: 1; }
        100%  { transform: translateX(0);     opacity: 0.7; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-bolt text-primary text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Eco<span class="text-primary">Charge</span></span>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="#" class="border-primary text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Home
                        </a>
                        <a href="user_search_station.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Stations
                        </a>
                        <a href="user_battery_view.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Batteries
                        </a>
                        <a href="user_slot_view_bookings.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                           Booked Slots Details
                        </a>
                        <a href="user_battery_bookings.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Booked Battery Details  
                        </a>
                        <a href="user_feedback.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Feedback
                        </a>
                        <a href="user_profile.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Profile
                        </a>
                        <a href="user_logout.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Logout
                        </a>
                    </div>
                </div>
            
        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="#" class="bg-primary bg-opacity-10 border-primary text-primary block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Home</a>
                <a href="#stations" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Stations</a>
                <a href="#batteries" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Batteries</a>
                <a href="#dashboard" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Dashboard</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="gradient-bg text-green-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 lg:py-24">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                <div class="mb-8 lg:mb-0">
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl mb-4">
                        Power Your Journey <br> Sustainably
                    </h1>
                    <p class="text-xl text-green-1000 text-opacity-90 max-w-3xl">
                        Find charging stations and rent batteries on the go with our eco-friendly platform. Join the green revolution today!
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <!-- Find Stations Button -->
                        <a href="user_find_station.php"
                        class="bg-transparent border-2 border-white hover:bg-white hover:bg-opacity-10 px-6 py-3 rounded-lg font-medium text-lg flex items-center justify-center transition">
                            <i class="fas fa-map-marker-alt mr-2"></i> Find Stations
                        </a>

                        <!-- Rent Battery Button -->
                        <a href="user_battery_view.php"
                        class="bg-transparent border-2 border-white hover:bg-white hover:bg-opacity-10 px-6 py-3 rounded-lg font-medium text-lg flex items-center justify-center transition">
                            <i class="fas fa-battery-three-quarters mr-2"></i> Rent Battery
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="py-16 px-4 md:px-12 bg-white mx-4 mt-8 rounded-lg shadow-md">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-green-700">Our Services & Benefits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Feature 1 -->
            <div class="flex flex-col items-center text-center p-6 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                <div class="bg-green-100 p-4 rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Fast Charging</h3>
                <p class="text-gray-600">Quickly power up your EV with our high-speed charging stations.</p>
            </div>
            <!-- Feature 2 -->
            <div class="flex flex-col items-center text-center p-6 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                <div class="bg-green-100 p-4 rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v2.102a1 1 0 01-.848.956L8 6V4a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h3a1 1 0 001-1v-2l3.248 1.3a1 1 0 01.848.956V18a1 1 0 01-.7.954L9 19.904A1 1 0 018 19v-2.102a1 1 0 01.848-.956L12 14V16a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3a1 1 0 00-1 1v2l-3.248-1.3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Battery Swapping</h3>
                <p class="text-gray-600">Swap your depleted battery for a fully charged one in minutes.</p>
            </div>
            <!-- Feature 3 -->
            <div class="flex flex-col items-center text-center p-6 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                <div class="bg-green-100 p-4 rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 110-6 3 3 0 010 6z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Eco-Friendly</h3>
                <p class="text-gray-600">Support a greener future with our sustainable energy solutions.</p>
            </div>
            <!-- Feature 4 -->
            <div class="flex flex-col items-center text-center p-6 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                <div class="bg-green-100 p-4 rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10v6a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 110-4 2 2 0 010 4zm7 0a2 2 0 110-4 2 2 0 010 4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Easy Payment</h3>
                <p class="text-gray-600">Seamless and secure payment options for all our services.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="bg-green text-green py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">1,250+</div>
                    <div class="text-sm uppercase tracking-wider">Charging Stations</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">3,500+</div>
                    <div class="text-sm uppercase tracking-wider">Available Slots</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">2,800+</div>
                    <div class="text-sm uppercase tracking-wider">Batteries</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">15,000+</div>
                    <div class="text-sm uppercase tracking-wider">Happy Users</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-green-900 text-white pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">EcoCharge</h3>
                    <p class="text-gray-400 text-sm">
                        Powering the future of sustainable transportation with innovative charging and battery solutions.
                    </p>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">Home</a></li>
                        <li><a href="#stations" class="text-gray-400 hover:text-white text-sm">Charging Stations</a></li>
                        <li><a href="#batteries" class="text-gray-400 hover:text-white text-sm">Battery Rental</a></li>
                        <li><a href="#dashboard" class="text-gray-400 hover:text-white text-sm">Dashboard</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary"></i>
                            <span>123 Green Avenue, Eco City, EC 12345</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-primary"></i>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-primary"></i>
                            <span>support@ecocharge.com</span>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <h4 class="text-sm font-bold mb-2">Download Our App</h4>
                        <div class="flex space-x-2">
                            <a href="#" class="bg-black bg-opacity-30 hover:bg-opacity-50 rounded-lg p-2 flex items-center">
                                <i class="fab fa-apple mr-2"></i>
                                <span class="text-xs">App Store</span>
                            </a>
                            <a href="#" class="bg-black bg-opacity-30 hover:bg-opacity-50 rounded-lg p-2 flex items-center">
                                <i class="fab fa-google-play mr-2"></i>
                                <span class="text-xs">Play Store</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
                <p>&copy; 2023 EcoCharge. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile menu toggle script -->
    <script>
        // Get references to the elements
        const openPopupBtn = document.getElementById('openPopupBtn');
        const loginModal = document.getElementById('loginModal');
        const closePopupBtn = document.getElementById('closePopupBtn');
        const modalContent = loginModal.querySelector('div'); // The inner div of the modal

        // Function to open the modal
        function openModal() {
            loginModal.classList.remove('hidden');
            // Trigger reflow to ensure transitions apply
            void loginModal.offsetWidth;
            loginModal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }

        // Function to close the modal
        function closeModal() {
            loginModal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                loginModal.classList.add('hidden');
            }, 300); // Match this with the transition duration
        }

        // Event listeners
        openPopupBtn.addEventListener('click', openModal);
        closePopupBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside the content box
        loginModal.addEventListener('click', function(event) {
            if (event.target === loginModal) { // Check if the click was directly on the backdrop
                closeModal();
            }
        });

        // Close modal when pressing the Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !loginModal.classList.contains('hidden')) {
                closeModal();
            }
        });
    </script>
</body>
</html>