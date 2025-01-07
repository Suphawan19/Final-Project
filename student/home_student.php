<?php
include '../config.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลโพสต์พร้อมชื่อผู้โพสต์จากตาราง users
$sql = "SELECT posts.*, users.first_name FROM posts
        LEFT JOIN users ON posts.user_id = users.user_id
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

// เพิ่มจำนวนไลค์ในฐานข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['like_post_id'])) {
    $post_id = $_POST['like_post_id'];

    // เช็คว่าไลค์เป็นของโพสต์นี้
    $sql = "UPDATE posts SET likes_count = likes_count + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        // ถ้าสำเร็จ รีเฟรชหน้าโพสต์
        header("Location: home_student.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Student</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ลิงก์ไปยังไฟล์ CSS -->
    <link rel="stylesheet" href="../style.css/style_home_student.css">



</head>


<body>

    <div class="sidebar" id="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="home_student.php"><i class="fas fa-calendar-alt"></i> Home</a></li>
            <li><a href="../student/Posts.php"><i class="fas fa-users-cog"></i> Post</a></li>
            <li><a href="../student/notification_student.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li class="menu-item dropdown">
                <a href="#" id="dropdown-toggle" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-info-circle"></i> About Us</a>
                <ul class="dropdown-menu">
                    <li><a href="../student/student_information.php"><i class="fas fa-user-graduate"></i> Student Information</a></li>
                    <li><a href="../student/student_schedule.php"><i class="fas fa-calendar-alt"></i> Class Schedule</a></li>
                    <li><a href="../student/student_exam_schedule.php"><i class="fas fa-calendar-alt"></i> Examination Schedule</a></li>
                </ul>
            </li>
            <!-- ลิงก์สำหรับเปลี่ยนรหัสผ่าน -->
            <li><a href="../student/student_change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="header">Welcome to Students!</div>
    </div>
     <!-- Hero Banner -->
     <section class="hero-banner bg-primary text-white text-center py-5">
        <h1>📢 Important Events Notification</h1>
        <p class="lead">Keep track of all important events in one place!</p>
        <a href="#event-overview" class="btn btn-light btn-lg"> View Events</a>
    </section>
    <main class="container mt-4">
    <section id="event-overview" class="event-section">
        <div class="row g-4">
            <?php
            // กรองเหตุการณ์ที่มี role เป็น student
            $sql_events = "SELECT * FROM events WHERE role = 'student' ORDER BY set_event_date DESC";
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
    <script>
        function showEventDetails(title, eventType, startDate, endDate, startTime, endTime, description) {
    document.getElementById('event-title').innerText = title;
    document.getElementById('event-type').innerText = eventType;
    document.getElementById('event-Set_event_date').innerText = startDate;
    document.getElementById('event-end').innerText = endDate;
    document.getElementById('event-time').innerText = startTime;
    document.getElementById('event-end_time').innerText = endTime;
    document.getElementById('event-description').innerText = description;

    var eventDetailsModal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
    eventDetailsModal.show();
}

    </script>
    <div class="container">
        <h1 class="my-4 text-center">Student Posts</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post-container">
                    <h2 class="post-author">Posted by: <?= htmlspecialchars($row['first_name']); ?></h2>
                    <p class="post-title"><?= htmlspecialchars($row['title']); ?></p>
                    <p class="post-description"><?= nl2br(htmlspecialchars($row['description'])); ?></p>

                    <?php if (!empty($row['image_path'])): ?>
                        <div class="post-image">
                            <img src="../uploads/<?= htmlspecialchars($row['image_path']); ?>" alt="Post Image">
                        </div>
                    <?php endif; ?>

                    <!-- Like and Comment Section -->
                    <div class="post-actions">
                        <?php
                        // ดึงจำนวนไลค์จากฐานข้อมูล
                        $post_id = $row['id'];
                        $like_sql = "SELECT likes_count FROM posts WHERE id = ?";
                        $stmt = $conn->prepare($like_sql);
                        $stmt->bind_param("i", $post_id);
                        $stmt->execute();
                        $like_result = $stmt->get_result();
                        $like_count = $like_result->fetch_assoc()['likes_count'];
                        ?>
                        <form action="home_student.php" method="POST" class="like-form">
                            <button type="submit" class="like-btn">
                                <i class="fas fa-thumbs-up"></i> Like (<span id="like-count-<?= $row['id']; ?>"><?= $like_count; ?></span>)
                            </button>
                            <input type="hidden" name="like_post_id" value="<?= $row['id']; ?>">
                        </form>

                        <button class="comment-btn" onclick="toggleCommentSection(<?= $row['id']; ?>)">
                            <i class="fas fa-comment"></i> Comment
                        </button>
                    </div>

                    <!-- Number of Comments -->
                    <?php
                    // นับจำนวนคอมเมนต์ที่โพสต์นี้
                    $count_comments_sql = "SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = ?";
                    $stmt = $conn->prepare($count_comments_sql);
                    $stmt->bind_param("i", $post_id);
                    $stmt->execute();
                    $result_count = $stmt->get_result()->fetch_assoc();
                    $comment_count = $result_count['comment_count'];
                    ?>
                    <p><strong>Comments (<?= $comment_count; ?>)</strong></p>

                    <div class="comments-section" id="comment-section-<?= $row['id']; ?>" style="display: none;">
                        <form action="comment.php" method="POST" class="comment-form">
                            <input type="hidden" name="post_id" value="<?= $row['id']; ?>">
                            <input type="text" name="author_name" placeholder="Your Name" required>
                            <textarea name="comment" placeholder="Write a comment..." required></textarea>
                            <button type="submit">Post Comment</button>
                        </form>

                        <!-- Display Comments -->
                        <?php
                        $comment_sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC";
                        $stmt = $conn->prepare($comment_sql);
                        $stmt->bind_param("i", $post_id);
                        $stmt->execute();
                        $comments = $stmt->get_result();

                        if ($comments->num_rows > 0):
                            while ($comment = $comments->fetch_assoc()): ?>
                                <div class="comment">
                                    <strong><?= htmlspecialchars($comment['author_name']); ?></strong>
                                    <p><?= nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                    <small>Posted on: <?= date('d M Y H:i', strtotime($comment['created_at'])); ?></small>
                                </div>
                            <?php endwhile;
                        else: ?>
                            <p>No comments yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>

    <script>
        function toggleCommentSection(postId) {
            const section = document.getElementById('comment-section-' + postId);
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
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
                    <li><a href="../admin/admin_student.php">student infomation</a></li>
                    <li><a href="../student/student_schedule.php">schedule infomation</a></li>
                    <li><a href="../student/student_exam_schedule.php">exam infomation schedule</a></li>
                    <li><a href="../student/student_change_password.php">change password</a></li>
                </ul>
            </div>

            <!-- Useful Links -->
            <div class="footer-section links">
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="../student/notification_student.php">notifications</a></li>
                    <li><a href="../student/home_student.php">post home student</a></li>

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
