<?php
require_once 'dbconnection.php';

// Set the header to return JSON
header('Content-Type: application/json');

// Connect to the database
$conn = dbconnect();

$response = [];

if (isset($_GET['companyId']) && !empty($_GET['companyId'])) {
    $companyId = (int)$_GET['companyId'];
    
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM CompanyDetails WHERE CompanyID = ?");
    $stmt->bind_param("i", $companyId);
    $stmt->execute();
    
    // Get the result of the query
    $companyResult = $stmt->get_result();
    
    if ($companyDetails = $companyResult->fetch_assoc()) {
        // We have company details, now get products
        $response['success'] = true;
        $response['data']['companyDetails'] = $companyDetails;
        
        // Assuming 'UserID' is the connecting field between 'CompanyDetails' and 'Products'
        $userId = $companyDetails['UserID'];
        
        // Now get products for this company
        $productStmt = $conn->prepare("SELECT * FROM Products WHERE UserID = ?");
        $productStmt->bind_param("i", $userId);
        $productStmt->execute();
        
        $productsResult = $productStmt->get_result();
        $products = $productsResult->fetch_all(MYSQLI_ASSOC);
        
        if ($products) {
            $response['data']['products'] = $products;
        } else {
            $response['data']['products'] = []; // No products found
        }
        
        $productStmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "No company found with ID $companyId";
    }
    
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Company ID not provided";
}

$conn->close();

// Send the response
echo json_encode($response);

// End the script to prevent any further accidental output
exit;
?>