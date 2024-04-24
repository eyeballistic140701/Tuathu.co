<?php 
include('dbconnection.php');

$error_message = ''; // Initialize error message

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $conn = dbconnect();

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    // Check if POST variables are set and not empty
    if(
        isset($_POST['fname']) && !empty($_POST['fname']) &&
        isset($_POST['lname']) && !empty($_POST['lname']) &&
        isset($_POST['uname']) && !empty($_POST['uname']) &&
        isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['password']) && !empty($_POST['password']) &&
        isset($_POST['phone']) && !empty($_POST['phone'])
    ) {
        // Validate and sanitize input
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $username = trim($_POST['uname']);
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $phone = trim($_POST['phone']);
    } else {
        $error_message = "All fields are required.";
    }

    // Additional validation (for example, for the email)
    if (!$email && empty($error_message)) {
        $error_message = "Invalid email format";
    }

    if(empty($error_message)) {
        // Using prepared statement to insert the data
        $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, password, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fname, $lname, $username, $hashedPassword, $email, $phone);

        if ($stmt->execute()) {
            header('Location: ./login.php');
            exit;
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    
    $conn->close();
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
      <link rel="stylesheet" href="./style/register.css" />

    </head>

<body>
  <div id="nav-placeholder"></div>

    <div class="form-container">
        <h2>Register a New User</h2>
        <!-- Display error message -->
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form id="registrationForm" action="register.php" method="post">
            <div id="div">
                <input type="text" id="fname" name="fname" placeholder="First Name" required>
                <span class="error-msg" id="fnameError"></span>
            </div>

            <div id="div">
                <input type="text" id="lname" name="lname" placeholder="Last Name" required>
                <span class="error-msg" id="lnameError"></span>
            </div>
            
            <div id="div">
                <input type="text" id="uname" name="uname" placeholder="Username" required>
                <span class="error-msg" id="unameError"></span>
            </div>

            <div id="div">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <span class="error-msg" id="emailError"></span>
            </div>
          
            <div id="div">
                <input type="number" id="phone" name="phone" placeholder="Phone Number" required>
                <span class="error-msg" id="phoneError"></span>
            </div>
            
            <div id="div">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="error-msg" id="passwordError"></span>
            </div>

            
            <div id="div">
                <input type="submit" value="Register">
            </div>

            <!-- Button to go to the login page -->
            <a href="/login.php" class="login-btn">Login</a>
        </form>
    </div>


    <script>
        
    </script>

    <script src="./js/register.js"></script>
</body>

</html>

