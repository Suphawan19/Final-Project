<?php
include '../config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['event_id']) && isset($_POST['status'])) {
        $event_id = htmlspecialchars($_POST['event_id']);
        $event_status = htmlspecialchars($_POST['status']);

        // Fetch event details
        $sql_event = "SELECT * FROM events WHERE id = '$event_id'";
        $result_event = $conn->query($sql_event);

        if ($result_event->num_rows > 0) {
            $event = $result_event->fetch_assoc();

            // Fetch students for notification
            $sql_students = "SELECT * FROM students WHERE role = 'student'"; // Adjust accordingly
            $result_students = $conn->query($sql_students);

            if ($result_students->num_rows > 0) {
                while ($student = $result_students->fetch_assoc()) {
                    $student_email = $student['email']; // Assuming student has an email field

                    // Example: Sending an email notification
                    $subject = "Notification for Event: " . $event['title'];
                    $message = "Dear " . $student['name'] . ",\n\n";
                    $message .= "You have been invited to the event: " . $event['title'] . "\n";
                    $message .= "Event Date: " . $event['Set_event_date'] . "\n";
                    $message .= "Event Status: " . ($event_status == 'pending' ? 'Pending' : 'Sent') . "\n\n";
                    $message .= "Best regards,\nYour University";

                    // Send email (Make sure your server is configured for email sending)
                    mail($student_email, $subject, $message);
                }

                echo "<p>Notifications sent successfully to students.</p>";
            } else {
                echo "<p>No students found.</p>";
            }
        } else {
            echo "<p>Event not found.</p>";
        }
    } else {
        echo "<p>Invalid request.</p>";
    }
} else {
    echo "<p>Invalid request method.</p>";
}

// Close the database connection
$conn->close();
?>
