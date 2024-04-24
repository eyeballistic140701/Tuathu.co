<?php
session_start();

// Check if the user is logged in
if (empty($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

require_once 'dbconnection.php';
$conn = dbconnect();

$userID = $_SESSION['userID'];

// Prepare the statement to fetch company details
$stmt = $conn->prepare("SELECT CompanyName, Location, ContactInfo FROM CompanyDetails WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();

// Check if we got a result
if ($companyDetails = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'data' => $companyDetails]);
} else {
    echo json_encode(['success' => false, 'message' => 'No company details found']);
}

$stmt->close();
$conn->close();
?>
