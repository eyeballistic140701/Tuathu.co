<?php
session_start();

if (empty($_SESSION['userID'])) {
    echo json_encode([]);
    exit;
}

require_once 'dbconnection.php';
$conn = dbconnect();

$userID = $_SESSION['userID'];

$stmt = $conn->prepare("SELECT ProductTitle, ProductPrice, ProductCategory FROM Products WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($products);

$stmt->close();
$conn->close();
?>