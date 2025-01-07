<?php
include('../config.php');

// คำสั่ง SQL สำหรับดึงจำนวนเหตุการณ์ที่เกิดขึ้นในแต่ละเดือน
$sql_events_per_month = "
    SELECT MONTH(Set_event_date) AS month, COUNT(*) AS event_count 
    FROM events 
    GROUP BY MONTH(Set_event_date)
    ORDER BY MONTH(Set_event_date)";

$result_events_per_month = $conn->query($sql_events_per_month);

$event_counts = [];
$months = [];

if ($result_events_per_month->num_rows > 0) {
    // เตรียมข้อมูลสำหรับกราฟ
    while ($row = $result_events_per_month->fetch_assoc()) {
        $months[] = date('M', mktime(0, 0, 0, $row['month'], 10)); // เปลี่ยนตัวเลขเดือนเป็นชื่อเดือน (Jan, Feb, ...)
        $event_counts[] = $row['event_count'];
    }
} else {
    $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun"];
    $event_counts = [0, 0, 0, 0, 0, 0];
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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li class="nav-item"><a class="nav-link" href="../admin/courses.php"><i class="fas fa-graduation-cap""></i> Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/instructors.php"><i class="fas fa-chalkboard-teacher"></i> Instructors</a></li>
                    <li class="menu-item dropdown">
                        <a href="#" id="dropdown-toggle" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-info-circle"></i> About Us</a>
                        <ul class="dropdown-menu">
                            <li><a href="../admin/admin_student.php"><i class="fas fa-user-graduate"></i> Student Information</a></li>
                            <li><a href="../admin/admin_staff.php"><i class="fas fa-user-graduate"></i> Staff Information</a></li>
                            <li><a href="../admin/class_schedule.php"><i class="fas fa-calendar-alt"></i> Class Schedule</a></li>
                            <li><a href="../admin/exam_schedule.php"><i class="fas fa-calendar-alt"></i> Examination Schedule</a></li>
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
    <section id="event-overview">
        <div class="row g-4">
            <?php
            $sql_events = "SELECT * FROM events ORDER BY Set_event_date DESC";
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
                    $description = htmlspecialchars($row['description']);

                    echo "
                    <div class='col-lg-4 col-md-6 col-sm-12'>
                        <div class='card event-card'>
                            <div class='card-body'>
                                <h5 class='card-title'>$title</h5>
                                <p class='card-text'><strong>Type:</strong> $event_type</p>
                                <p class='card-text'><strong>Start:</strong> $set_event_date</p>
                                <p class='card-text'><strong>Description:</strong> $description</p>
                                <button class='btn btn-details' 
                                    onclick='showEventDetails(
                                        \"" . addslashes($title) . "\", 
                                        \"" . addslashes($event_type) . "\", 
                                        \"$set_event_date\", 
                                        \"$end_date\", 
                                        \"$event_time\", 
                                        \"$end_time\", 
                                        \"" . addslashes($description) . "\"
                                    )'>
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

<!-- Modal สำหรับแสดงรายละเอียดเหตุการณ์ -->
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

<script>
    function showEventDetails(title, eventType, setEventDate, endDate, eventTime, endTime, description) {
        document.getElementById('event-title').textContent = title;
        document.getElementById('event-type').textContent = eventType;
        document.getElementById('event-Set_event_date').textContent = setEventDate;
        document.getElementById('event-end').textContent = endDate;
        document.getElementById('event-time').textContent = eventTime;
        document.getElementById('event-end_time').textContent = endTime;
        document.getElementById('event-description').textContent = description;

        const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
        modal.show();
    }
</script>

<!-- Weather Section -->
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

    function fetchWeatherData() {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const temperature = data.main.temp;
                const minTemp = data.main.temp_min;
                const maxTemp = data.main.temp_max;
                const humidity = data.main.humidity;
                const windSpeed = data.wind.speed;
                const weatherDescription = data.weather[0].description;
                const weatherIcon = data.weather[0].icon;

                document.getElementById('temperature').textContent = `Current Temperature: ${temperature} °C`;
                document.getElementById('min-max-temp').textContent = `Min/Max Temperature: ${minTemp} °C / ${maxTemp} °C`;
                document.getElementById('humidity').textContent = `Humidity: ${humidity}%`;
                document.getElementById('wind-speed').textContent = `Wind Speed: ${windSpeed} m/s`;
                document.getElementById('weather-description').textContent = `Condition: ${weatherDescription}`;

                const iconUrl = `https://openweathermap.org/img/wn/${weatherIcon}@2x.png`;
                const weatherIconDiv = document.getElementById('weather-icon');
                weatherIconDiv.innerHTML = '';
                const iconElement = document.createElement('img');
                iconElement.src = iconUrl;
                iconElement.alt = weatherDescription;
                iconElement.style.width = "100px";
                weatherIconDiv.appendChild(iconElement);
            })
            .catch(error => console.error("Error fetching weather data:", error));
    }

    fetchWeatherData();
    setInterval(fetchWeatherData, 600000); // Refresh every 10 minutes
</script>

<!-- Event Statistics Chart Section -->
<section class="container my-5">
    
    <div class="chart-container" style="background-color:hsla(160, 4.30%, 27.10%, 0.19); padding: 30px; border-radius: 10px;">
    <h2 class="text-center mb-4" style="color: #fff; font-weight: bold; font-size:30px;">📊 Monthly Event Statistics</h2>
        <canvas id="eventChart" width="400" height="200" ></canvas>
    </div>
</section>

<script>
    
    // ดึงข้อมูลที่ถูกส่งมาจาก PHP
    const eventCounts = <?php echo json_encode($event_counts); ?>;
    const months = <?php echo json_encode($months); ?>;

    // สร้างกราฟด้วย Chart.js
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('eventChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // กราฟแท่ง
            data: {
                labels: months, // เดือน
                datasets: [{
                    label: 'Number of Events',
                    data: eventCounts, // จำนวนเหตุการณ์ในแต่ละเดือน
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)', 
                        'rgba(255, 99, 132, 0.6)', 
                        'rgba(54, 162, 235, 0.6)', 
                        'rgba(255, 206, 86, 0.6)', 
                        'rgba(153, 102, 255, 0.6)', 
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)', 
                        'rgba(255, 99, 132, 1)', 
                        'rgba(54, 162, 235, 1)', 
                        'rgba(255, 206, 86, 1)', 
                        'rgba(153, 102, 255, 1)', 
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    title: { 
                        display: true, 
                        text: 'Monthly Event Statistics', 
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        padding: { top: 10, bottom: 30 }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false // ซ่อนกริดของแกน x
                        },
                        ticks: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)' // กริดสีอ่อน
                        },
                        ticks: {
                            font: {
                                size: 14
                            },
                            stepSize: 5
                        }
                    }
                }
            }
        });
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
            <li><a href="../admin/courses.php">courses</a></li>
            <li><a href="../admin/instructors.php">instructors</a></li>
            <li><a href="../admin/event_display.php">event display</a></li>
            <li><a href="../admin/class_schedule.php">Class Schedule</a></li>
            <li><a href="../admin/exam_schedule.php">Exam Schedule</a></li>
            <li><a href="../admin/admin_student.php">Student Information</a></li>
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
        <a href="https://www.facebook.com/phuxuan.edu.vn?locale=th_TH" target="_blank" class="social-icon" title="Facebook" style="color:rgb(255, 255, 255); background-color:rgb(27, 99, 255);">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://phuxuan.edu.vn/" target="_blank" class="social-icon" title="Website" style="color: #4caf50; background-color:rgb(248, 248, 248);">
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
</body>
</html>
