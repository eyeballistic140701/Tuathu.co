<?php
require_once 'dbconnection.php';

header('Content-Type: application/json');

$categoryMap = [
    'cat1' => 'cat1',
    'cat2' => 'cat2',
    'cat3' => 'cat3',
    'cat4' => 'cat4',
    'cat5' => 'cat5',
    'cat6' => 'cat6'
];

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $categoryCode = $_GET['category'];

    if (!array_key_exists($categoryCode, $categoryMap)) {
        echo json_encode(['error' => 'Invalid category selected']);
        exit;
    }

    $conn = dbconnect();
    
    if ($conn->connect_error) {
        echo json_encode(['error' => 'Database connection error']);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT ProductTitle, ProductPrice FROM Products WHERE ProductCategory = ?");
    $stmt->bind_param("s", $categoryMap[$categoryCode]);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode($products);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([]);
}
?>
