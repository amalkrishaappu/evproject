# EV Project - Smart EV Charging & Battery Swap Management System

## Overview

EV Project is a web-based platform designed to simplify electric vehicle charging and battery-swapping services. The system connects EV users with charging stations, enabling slot booking, battery rental, battery return management, and station administration through a centralized platform.

The application supports multiple user roles including administrators, charging station operators, and EV users.

---

## Features

### User Features

* User Registration and Login
* Profile Management
* Search Nearby Charging Stations
* View Station Details
* Charging Slot Booking
* Charging Slot Cancellation
* Battery Rental Booking
* Battery Return Requests
* QR Code Payment Support
* RFID Payment Support
* Mobile Payment Integration
* Booking History
* Feedback Submission

### Charging Station Features

* Station Registration and Login
* Station Profile Management
* Add Charging Slots
* Edit Charging Slots
* View Slot Bookings
* Add Battery Inventory
* Edit Battery Inventory
* Manage Battery Returns
* View User Battery Bookings
* View User Slot Bookings
* View Customer Feedback
* Reports and Analytics Dashboard

### Administrator Features

* Secure Admin Login
* Approve Charging Stations
* Reject Charging Stations
* View Registered Users
* Delete Users
* Monitor User Activities
* Manage Stations
* System Dashboard

---

## Technology Stack

### Frontend

* HTML5
* CSS3
* JavaScript
* Responsive UI Design

### Backend

* PHP

### Database

* MySQL

### Web Server

* Apache (XAMPP/WAMP/LAMP)

### Version Control

* Git
* GitHub

---

## Project Structure

```text
├── admin_dashboard.php
├── admin_login.php
├── admin_view_stations.php
├── admin_view_user_details.php
├── station_dashboard.php
├── station_login.php
├── station_register.php
├── station_profile.php
├── station_battery_add.php
├── station_battery_edit.php
├── station_slot_add.php
├── station_slot_edit.php
├── user_login.php
├── user_register.php
├── user_home.php
├── user_profile.php
├── user_search_station.php
├── user_slot_book.php
├── user_battery_booking.php
├── db.php
├── database/
└── assets/
```

---

## Database Design

### Users Table

Stores registered EV users.

Fields:

* id
* name
* email
* password
* address
* phone number
* profile image

### Stations Table

Stores charging station information.

Fields:

* id
* station name
* location
* latitude
* longitude
* total slots
* available slots
* charging type
* status

### Battery Table

Stores battery inventory.

Fields:

* id
* brand
* model
* capacity
* range
* torque
* condition
* battery life
* station id
* image

---

## Core Modules

### Authentication Module

* Admin Login
* Station Login
* User Login
* Session Management

### Charging Slot Management

* Slot Creation
* Slot Availability Tracking
* Slot Booking
* Slot Cancellation

### Battery Rental Management

* Battery Listing
* Battery Booking
* Battery Return Workflow
* Battery Status Monitoring

### Payment Module

* QR Payment
* RFID Payment
* Mobile Payment

### Feedback System

* User Reviews
* Station Feedback Tracking

### Reporting System

* User Reports
* Booking Reports
* Station Activity Reports

---

## Installation Guide

### Prerequisites

* PHP 8.x or later
* MySQL
* Apache Server
* XAMPP Recommended

### Setup Steps

1. Clone the repository

```bash
git clone https://github.com/amalkrishaappu/evproject.git
```

2. Move the project folder into the XAMPP htdocs directory.

3. Create a MySQL database:

```sql
CREATE DATABASE ev_project;
```

4. Import the SQL file located in the database folder.

5. Configure database credentials inside:

```php
db.php
```

```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ev_project";
```

6. Start Apache and MySQL.

7. Open the application:

```text
http://localhost/ev_project-finalfile/
```

---

## Security Improvements Recommended

* Use Password Hashing (password_hash())
* Use Prepared Statements
* Add CSRF Protection
* Input Validation and Sanitization
* Role-Based Access Control
* Secure Session Handling

---

## Future Enhancements

* Real-Time Charging Slot Tracking
* Online Payment Gateway Integration
* Mobile Application
* Battery Health Prediction
* AI-Based Station Recommendation
* GPS Navigation Support
* Email Notifications
* Push Notifications

---

## Author

Amal Krishna

---

## License

This project is developed for educational and academic purposes. Feel free to modify and extend it according to your requirements.

