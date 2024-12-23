
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Role</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <style>
        /* Global Styles */
body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    background-image:url(images/dai-hoc-phu-xuan-2023-mau-do.jpeg);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #444;
}

.container {
    text-align: center;
    background: #fff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 600px;
}

h1 {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #333;
}

p {
    margin-bottom: 30px;
    color: #666;
}

.role-selection {
    display: flex;
    justify-content: space-around;
    gap: 20px;
}

.role-button {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    text-decoration: none;
    color: #444;
    padding: 20px;
    width: 120px;
    height: 120px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.role-button:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.role-button i {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.role-button span {
    font-size: 1rem;
    font-weight: bold;
}

.role-button.admin {
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    color: white;
}

.role-button.staff {
    background: linear-gradient(135deg, #1dd1a1, #10ac84);
    color: white;
}

.role-button.student {
    background: linear-gradient(135deg, #48dbfb, #2e86de);
    color: white;
}
    </style>
    <div class="container">
        <h1>Select Your Role</h1>
        <p>Please choose your role to proceed to the login page:</p>
        <div class="role-selection">
            <a href="login.php" class="role-button admin">
                <i class="fas fa-user-shield"></i>
                <span>Admin</span>
            </a>
            <a href="login.php" class="role-button staff">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Staff</span>
            </a>
            <a href="login.php" class="role-button student">
                <i class="fas fa-user-graduate"></i>
                <span>Student</span>
            </a>
        </div>
    </div>
</body>
</html>