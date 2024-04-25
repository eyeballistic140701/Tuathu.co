<?php
require_once 'dbconnection.php';
session_start();
$conn = dbconnect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username_or_email) || empty($password)) {
        $_SESSION['login_error'] = "Both fields are required!";
        header('location: login.php');
        exit();
    }

    // Fetch the user ID as well
    $sql = "SELECT id, password FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    if ($row && password_verify($password, $row['password'])) {
        // Set the user's ID in the session
        $_SESSION['userID'] = $row['id'];
        header('location: /dashboard.php');
        exit();
    } else {
        $_SESSION['login_error'] = "Incorrect username/email and password";
        header('location: login.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuathu.co</title>
    <!-- External CSS/JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <script src="./js/nav.js"></script>
    <link rel="stylesheet" href="./style/nav.css" />
    <link rel="stylesheet" href="./style/login.css" />
</head>
<body>
    <div id="nav-placeholder"></div>

    <div class="form-container">
        <h2>Login</h2>
        <!-- Error message container -->
        <div id="error-message" style="color: red;"></div>
        <div class="form-inputs">
            <form action="login.php" method="post">
                <div><input type="text" id="uname_or_email" name="username" placeholder="Username or Email" title="Enter a valid email"></div>
                <div><input type="password" id="login_password" name="password" placeholder="Password" required maxlength="26" title="Password should be 6 characters long"></div>
                <div><input type="submit" value="Login"></div>
            </form>
            <a href="/register.php" class="register-btn">Register</a>
        </div>
    </div>

    <!-- JavaScript for displaying errors -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorMessage = <?php echo json_encode(isset($_SESSION['login_error']) ? $_SESSION['login_error'] : ''); ?>;
            if (errorMessage) {
                document.getElementById('error-message').textContent = errorMessage;
                // Clear the session error to prevent it from sticking around on refresh or navigation
                <?php unset($_SESSION['login_error']); ?>
            }
        });
    </script>
</body>
</html>
