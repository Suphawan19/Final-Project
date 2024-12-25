<?php
// เชื่อมต่อกับฐานข้อมูล
include('../config.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title'], $_POST['event_type'], $_POST['month'], $_POST['Set_event_date'], $_POST['end_date'], $_POST['time'])) {
        $title = $_POST['title'];
        $event_type = $_POST['event_type'];
        $month = $_POST['month'];
        $Set_event_date = $_POST['Set_event_date'];
        $end_date = $_POST['end_date'];
        $time = $_POST['time'];
        $end_time = $_POST['end_time']; // Retrieve the end time

        // SQL insert statement
        $sql = "INSERT INTO events (title, event_type, month, Set_event_date, end_date, event_time, end_time) 
        VALUES ('$title', '$event_type', '$month', '$Set_event_date', '$end_date', '$time', '$end_time')";

        if ($conn->query($sql)) {
            echo "🎉 Event added successfully!";
        } else {
            echo "❌ Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Important Event Notification System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../style.css/style_home_admin.css">

</head>
<body>
    
<!-- ส่วนหัว -->
<header>
    <h1>Welcome to system admin!</h1>
    <nav class="navbar navbar-expand-lg custom-shadow-bg">
        <div class="container-fluid">
            <a class="navbar-brand text-yellow" href="#"><i class="fas fa-home"></i> Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="event_display.php"><i class="fas fa-calendar-alt"></i> Event Display</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/user_management.php"><i class="fas fa-users"></i> User Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/send_notification.php"><i class="fas fa-bell"></i> Notifications</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/courses.php"><i class="fas fa-graduation-cap""></i> courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/instructors.php"><i class="fas fa-chalkboard-teacher"></i> instructors</a></li>
                    <li class="menu-item dropdown">
                        <a href="#" id="dropdown-toggle" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-info-circle"></i> About Us</a>
                        <ul class="dropdown-menu">
                            <li><a href="../admin/admin_student.php"><i class="fas fa-user-graduate"></i> Student Information</a></li>
                            <li><a href="../admin/class_schedule.php"><i class="fas fa-calendar-alt"></i> Class Schedule</a></li>
                            <li><a href="../admin/exam_schedule.php"><i class="fas fa-calendar-alt"></i> Examination Schedule</a></li>
                            <li><a href="../admin/career guidance.php"><i class="fas fa-chalkboard-teacher"></i> Career Guidance Training</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>


    <!-- Hero Banner -->
    <section class="hero-banner">
        <h1>📢 Important Events Notification</h1>
        <p class="lead">Keep track of all important events in one place!</p>
        <a href="#event-overview" class="btn btn-warning btn-lg">View Events</a>
    </section>

    <!-- Event Section -->
    <main class="container mt-4">
        <section id="event-overview" class="event-section">
            <div class="row g-4">
                <?php
                $sql_events = "SELECT * FROM events ORDER BY Set_event_date DESC";
                $result_events = $conn->query($sql_events);
                if ($result_events->num_rows > 0) {
                    while ($row = $result_events->fetch_assoc()) {
                        $id = $row['id'];
                        $title = htmlspecialchars($row['title']);
                        $event_type = htmlspecialchars($row['event_type']);
                        $Set_event_date = htmlspecialchars($row['Set_event_date']);
                        $end_date = htmlspecialchars($row['end_date']);
                        $event_time = htmlspecialchars($row['event_time']);
                        $end_time = htmlspecialchars($row['end_time']);

                        echo "
                        <div class='col-lg-4 col-md-6 col-sm-12'>
                            <div class='card event-card'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$title</h5>
                                    <p class='card-text'><strong>Type:</strong> $event_type</p>
                                    <p class='card-text'><strong>Start:</strong> $Set_event_date</p>
                                    <button class='btn btn-details' onclick='showEventDetails(\"$title\", \"$event_type\", \"$Set_event_date\", \"$end_date\", \"$event_time\", \"$end_time\")'>
                                         View Details
                                    </button>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p class='text-center'>🎉 No events available at the moment!</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="event-title">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Type:</strong> <span id="event-type"></span></p>
                    <p><strong>Start Date:</strong> <span id="event-Set_event_date"></span></p>
                    <p><strong>End Date:</strong> <span id="event-end"></span></p>
                    <p><strong>Start Time:</strong> <span id="event-time"></span></p>
                    <p><strong>End Time:</strong> <span id="event-end_time"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript for Weather API -->
    

    <!-- JavaScript for Event Modal -->
    <script>
        function showEventDetails(title, eventType, SetEventDate, endDate, eventTime, endTime) {
            document.getElementById('event-title').textContent = title;
            document.getElementById('event-type').textContent = eventType;
            document.getElementById('event-Set_event_date').textContent = SetEventDate;
            document.getElementById('event-end').textContent = endDate;
            document.getElementById('event-time').textContent = eventTime;
            document.getElementById('event-end_time').textContent = endTime;

            // Show the modal
            var eventDetailsModal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
            eventDetailsModal.show();
        }
    </script>
<!-- แสดงข้อมูลสภาพอากาศ -->
<div class="weather-container">
    <h1>Weather in Hue, Vietnam</h1>
    <div id="weather-icon"></div>
    <p id="temperature"></p>
    <p id="min-max-temp"></p>
    <p id="humidity"></p>
    <p id="wind-speed"></p>
    <p id="weather-description"></p>
</div>

<script>
    const apiKey = "26962102542b1dc4b023bf1654bb79c4"; // Your API key
    const city = "Hue";
    const country = "vn";
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city},${country}&appid=${apiKey}&units=metric`;

    // ฟังก์ชันดึงข้อมูลและอัปเดต DOM
    function fetchWeatherData() {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log("Weather data:", data); // ตรวจสอบข้อมูลใน console
                
                // ดึงข้อมูลจาก API
                const temperature = data.main.temp;
                const minTemp = data.main.temp_min;
                const maxTemp = data.main.temp_max;
                const humidity = data.main.humidity;
                const windSpeed = data.wind.speed;
                const weatherDescription = data.weather[0].description;
                const weatherIcon = data.weather[0].icon;

                // อัปเดตข้อมูลใน HTML
                document.getElementById('temperature').textContent = `Current Temperature: ${temperature} °C`;
                document.getElementById('min-max-temp').textContent = `Min/Max Temperature: ${minTemp} °C / ${maxTemp} °C`;
                document.getElementById('humidity').textContent = `Humidity: ${humidity}%`;
                document.getElementById('wind-speed').textContent = `Wind Speed: ${windSpeed} m/s`;
                document.getElementById('weather-description').textContent = `Condition: ${weatherDescription}`;

                // อัปเดตไอคอนสภาพอากาศ
                const iconUrl = `https://openweathermap.org/img/wn/${weatherIcon}@2x.png`;
                const weatherIconDiv = document.getElementById('weather-icon');

                // ลบไอคอนเก่า (ถ้ามี) เพื่อป้องกันการซ้อน
                weatherIconDiv.innerHTML = ''; // clear existing icon
                const iconElement = document.createElement('img');
                iconElement.src = iconUrl;
                iconElement.alt = weatherDescription;
                iconElement.style.width = "100px"; // Adjust size if needed
                weatherIconDiv.appendChild(iconElement);
            })
            .catch(error => console.error("Error fetching weather data:", error));
    }

    // เรียกฟังก์ชันครั้งแรกเมื่อโหลดหน้า
    fetchWeatherData();

    // ตั้งค่าการรีเฟรชข้อมูลทุก 10 นาที (600,000 ms)
    setInterval(fetchWeatherData, 600000); 
</script>
        <!-- Footer Section -->
        <footer class="footer">

<!-- Footer Content -->
<div class="footer-container">
    <!-- Left Section -->
    <div class="footer-section about">
        <img src="../images/logo pxu.jpeg" alt="Logo">
        
        <p>Phú Xuân University, located in Hue, Vietnam, is one of the most renowned higher education institutions in central Vietnam. It was established to provide quality education and promote research relevant to the development of local and national communities.</p>
        
    </div>

    <!-- Quick Links -->
    <div class="footer-section links">
        <h4>Quick Links</h4>
        <ul>
            <li><a href="../admin/admin_student.php">student infomation</a></li>
            <li><a href="../admin/class_schedule.php">schedule infomation</a></li>
            <li><a href="../admin/exam_schedule.php">exam infomation schedule</a></li>
            <li><a href="../admin/user_management.php">user management</a></li>
        </ul>
    </div>

    <!-- Useful Links -->
    <div class="footer-section links">
        <h4>Useful Links</h4>
        <ul>
            <li><a href="../admin/event_display.php">event display</a></li>
            <li><a href="../admin/career guidance.php">career guidance training</a></li>
            
        </ul>
    </div>

    <!-- School Hours -->
    <div class="footer-section hours">
        <h4>School Hours</h4>
        <p>7:00 AM - 4:30 PM ,  - Monday</p>
        <p>Phu Xuan University,<br>
        Phu Xuan University - 176 Tran Phu,Tp.Huế, Thừa Thiên Huế,<br>
        49000, Vietnam</p>
    </div>
</div>

<!-- Footer Bottom -->
<div class="footer-bottom">
    <p>Copyright © 2024 Phu Xuan University. All rights reserved.</p>
</div>
</footer>
</body>

</html>