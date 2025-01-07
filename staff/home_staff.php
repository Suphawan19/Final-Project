<?php
// เชื่อมต่อกับฐานข้อมูล
include('../config.php');
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
    <link rel="stylesheet" href="../style.css/style_home_staff.css">
</head>

<body>
    <!-- ส่วนหัว -->
    <header>
        <h1 class="text-center text-yellow py-3">Welcome to the Staff System!</h1>
        <nav class="navbar navbar-expand-lg custom-shadow-bg">
            <div class="container-fluid">
            <a class="navbar-brand text-yellow" href="#"><i class="fas fa-home"></i> Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href=""><i class="fas fa-calendar-check"></i> Event Display</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../staff/notifications_staff.php"><i class="fas fa-bell"></i> Notifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../staff/staff_change_password.php"><i class="fas fa-key"></i> Change Password</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-info-circle"></i> About Us
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item"href="../staff/staff_infomation.php"><i class="fas fa-user-graduate"></i> Staff Information</a></li>
                                <li><a class="dropdown-item" href="../admin/class_schedule.php"><i class="fas fa-calendar-alt"></i> Class Schedule</a></li>
                                <li><a class="dropdown-item" href="../admin/exam_schedule.php"><i class="fas fa-calendar-alt"></i> Examination Schedule</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Banner -->
    <section class="hero-banner bg-primary text-white text-center py-5">
        <h1>📢 Important Events Notification</h1>
        <p class="lead">Keep track of all important events in one place!</p>
        <a href="#event-overview" class="btn btn-light btn-lg"> View Events</a>
    </section>

    <!-- Event Section -->
    <main class="container mt-4">
        <section id="event-overview" class="event-section">
            <div class="row g-4">
                <?php
                $sql_events = "SELECT * FROM events WHERE role = 'staff' ORDER BY set_event_date DESC";
                $result_events = $conn->query($sql_events);
                if ($result_events->num_rows > 0) {
                    while ($row = $result_events->fetch_assoc()) {
                        $id = $row['id'];
                        $title = htmlspecialchars($row['title']);
                        $event_type = htmlspecialchars($row['event_type']);
                        $set_event_date = htmlspecialchars($row['set_event_date']);
                        $end_date = htmlspecialchars($row['end_date']);
                        $event_time = htmlspecialchars($row['event_time']);
                        $end_time = htmlspecialchars($row['end_time']);
                        $description = htmlspecialchars($row['description']);  // Get the description

                        echo "
                        <div class='col-lg-4 col-md-6 col-sm-12'>
                            <div class='card event-card'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$title</h5>
                                    <p class='card-text'><strong>Type:</strong> $event_type</p>
                                    <p class='card-text'><strong>Start:</strong> $set_event_date</p>
                                    <p class='card-text'><strong>Description:</strong> $description</p> <!-- Display description -->
                                    <button class='btn btn-details' onclick='showEventDetails(\"$title\", \"$event_type\", \"$set_event_date\", \"$end_date\", \"$event_time\", \"$end_time\", \"$description\")'>
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
                    <p><strong>Description:</strong> <span id="event-description"></span></p> 
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


    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');
        });
    </script>

    

<!-- Footer Section -->
<footer class="footer">
<!-- Footer Content -->
<div class="footer-container">
    <!-- Left Section -->
    <div class="footer-section about">
        <img src="../images/logo pxu.jpeg" alt="Logo">

        <p>Phú Xuân University, located in Hue,<br>
             Vietnam, is one of the most renowned higher education institutions in central <br>
             Vietnam. It was established to provide quality education and promote research relevant to the development of local and national communities.</p>

    </div>

        <!-- Quick Links -->
        <div class="footer-section links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="../admin/admin_student.php">Student Information</a></li>
                <li><a href="../admin/class_schedule.php">Class Schedule</a></li>
                <li><a href="../admin/exam_schedule.php">Exam Schedule</a></li>
                <li><a href="../admin/user_management.php">User Management</a></li>
            </ul>
        </div>

        <!-- School Hours -->
        <div class="footer-section hours">
            <h4>School Hours</h4>
            <p>8:00 AM - 4:30 PM, Thursday - Monday</p>
            <address>
            Phu Xuan University,<br>
            Phu Xuan University - 176 Tran Phu,Tp.Huế, Thừa Thiên Huế,<br>
            49000, Vietnam,<br>
            </address>
        </div>

           <!-- Social Media -->
<div class="footer-section social-media">
    <div class="social-icons">
        <a href="https://www.facebook.com/phuxuan.edu.vn?locale=th_TH" target="_blank" class="social-icon" title="Facebook" style="color:rgb(255, 255, 255); background-color:rgb(27, 99, 255); /* สีพื้นหลัง Facebook (สามารถเปลี่ยนได้) */">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://phuxuan.edu.vn/" target="_blank" class="social-icon" title="Website" style="color: #4caf50;  background-color:rgb(248, 248, 248); /* สีพื้นหลัง Facebook (สามารถเปลี่ยนได้) */">
            <i class="fas fa-globe"></i> <!-- ไอคอน Globe -->
        </a>
        <!-- เพิ่มไอคอนที่อยู่ -->
        <a href="https://www.google.com/maps?q=Phu+Xuan+University" target="_blank" class="social-icon" title="Address" style="color: rgb(255, 255, 255); background-color: rgb(255, 0, 0);">
            <i class="fas fa-map-marker-alt"></i> <!-- ไอคอนที่อยู่ -->
        </a>
    </div>
</div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <p>&copy; 2024 Phu Xuan University. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
