<?php
session_start();
require_once 'dbconnection.php';

// Check if the user is logged in and get their UserID
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
} else {
    // Handle the case where the user is not logged in
    die("User is not logged in.");
}

// Check if the form data is posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = dbconnect();

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $companyName = $_POST['companyName'];
    $companyLocation = $_POST['companyLocation'];
    $companyContact = $_POST['companyContact'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO CompanyDetails (UserID, CompanyName, Location, ContactInfo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userID, $companyName, $companyLocation, $companyContact);

    // Execute and check for errors
    if ($stmt->execute()) {
        echo "New records created successfully";
        // Redirect or do something upon success
    } else {
        echo "Error: " . $stmt->error;
        // Handle error
    }

    $stmt->close();
    $conn->close();
} else {
    // Handle the case where the form is not submitted
    die("No form data submitted.");
}
?>
