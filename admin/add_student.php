<?php
include '../config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receive form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Combine day, month, and year into a single date (YYYY-MM-DD)
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    // Convert the month name to a number
    $month_number = date('m', strtotime($month));

    // Combine into a date in the format YYYY-MM-DD
    $date_of_birth = "$year-$month_number-$day";

    $gender = $_POST['gender'];
    $faculty = $_POST['faculty'];
    $major = $_POST['major'];

    // Handle photo upload (optional)
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photo = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_extension = pathinfo($photo, PATHINFO_EXTENSION);
        $photo_new_name = uniqid('', true) . '.' . $photo_extension;
        $photo_path = '../uploads/' . $photo_new_name;

        // Move the uploaded file to the uploads directory
        if (!move_uploaded_file($photo_tmp, $photo_path)) {
            echo "Error uploading photo.";
            exit();
        }
    } else {
        // If no photo is uploaded, set a default photo or NULL
        $photo_new_name = 'default.jpg'; // Path to default image
    }

    // SQL query to insert data into the database, including the photo (if uploaded)
    $sql = "INSERT INTO students (first_name, last_name, email, phone_number, date_of_birth, gender, major, faculty, photo) 
            VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$date_of_birth', '$gender', '$major', '$faculty', '$photo_new_name')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_student.php"); // Redirect to student list page
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="../style.css/style_add_student.css" rel="stylesheet">
</head>

<body>

    <form method="POST" enctype="multipart/form-data">
        <h1>Add New Student</h1>
        <label>First Name:</label><br>
        <input type="text" name="first_name" required><br><br>

        <label>Last Name:</label><br>
        <input type="text" name="last_name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone Number:</label><br>
        <input type="text" name="phone_number" required><br><br>

        <label for="date_of_birth">Date of Birth (DD-Month-YYYY):</label>
        <select name="day" required>
            <?php
            // Create day options from 1 to 31
            for ($i = 1; $i <= 31; $i++) {
                echo "<option value=\"$i\">$i</option>";
            }
            ?>
        </select>

        <select name="month" required>
            <option value="January">January</option>
            <option value="February">February</option>
            <option value="March">March</option>
            <option value="April">April</option>
            <option value="May">May</option>
            <option value="June">June</option>
            <option value="July">July</option>
            <option value="August">August</option>
            <option value="September">September</option>
            <option value="October">October</option>
            <option value="November">November</option>
            <option value="December">December</option>
        </select>

        <select name="year" required>
            <?php
            // Create year options for the last 100 years
            $currentYear = date('Y');
            for ($i = $currentYear; $i >= 1900; $i--) {
                echo "<option value=\"$i\">$i</option>";
            }
            ?>
        </select><br><br>

        <label>Gender:</label><br>
        <select name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select><br><br>

        <label for="faculty">Faculty:</label>
        <select id="faculty" name="faculty" required onchange="updateMajorOptions()">
            <option value="Faculty of Technology-Business" <?php echo (isset($faculty) && $faculty == 'Faculty of Technology-Business') ? 'selected' : ''; ?>>Faculty of Technology-Business</option>
            <option value="Faculty of Foreign Languages-Tourism" <?php echo (isset($faculty) && $faculty == 'Faculty of Foreign Languages-Tourism') ? 'selected' : ''; ?>>Faculty of Foreign Languages-Tourism</option>
        </select>


        <label for="major">Major:</label>
        <select id="major" name="major" required>
            <!-- Major options will be populated dynamically based on the selected faculty -->
        </select>

        <script>
            function updateMajorOptions() {
                var faculty = document.getElementById('faculty').value;
                var majorSelect = document.getElementById('major');

                // Clear current options
                majorSelect.innerHTML = '';

                if (faculty == 'Faculty of Technology-Business') {
                    // Add relevant majors for Faculty of Technology-Business
                    var options = [
                        'Information Technology',
                        'Automotive Engineering Technology',
                        'Business Administration-Marketing'
                    ];
                } else if (faculty == 'Faculty of Foreign Languages-Tourism') {
                    // Add relevant majors for Faculty of Foreign Languages-Tourism
                    var options = [
                        'Travel and Tourism Service Management',
                        'English language',
                        'Chinese language'
                    ];
                } else {
                    var options = [];
                }

                // Add options to the major select
                options.forEach(function(major) {
                    var option = document.createElement('option');
                    option.value = major;
                    option.textContent = major;
                    majorSelect.appendChild(option);
                });

                // Set the selected major if it's already in the student data
                <?php if (isset($student['major'])): ?>
                    var selectedMajor = "<?php echo $student['major']; ?>";
                    if (options.includes(selectedMajor)) {
                        majorSelect.value = selectedMajor;
                    }
                <?php endif; ?>
            }

            // Call the function to populate the majors based on the initial faculty value
            updateMajorOptions();
        </script>


        <!-- Add Photo Upload -->
        <label>Photo (Optional):</label><br>
        <input type="file" name="photo"><br><br>
        <button type="submit">Add Student</button>
    </form>

</body>

</html>