<?php
require_once 'dbconnection.php'; // Ensures the database connection file is included

header('Content-Type: application/json'); // Sets the content type of the response to JSON

// Check if a county has been selected and is not empty
if (isset($_GET['county']) && !empty($_GET['county'])) {
    $county = $_GET['county']; // Assigns the county variable from the GET request

    $conn = dbconnect(); // Establishes a database connection
    
    // Checks for a successful connection
    if ($conn->connect_error) {
        echo json_encode(['error' => 'Database connection error']); // Returns an error if the connection failed
        exit;
    }
    
    // Prepares the SQL query using placeholders for parameters
    $stmt = $conn->prepare("SELECT * FROM CompanyDetails WHERE Location = ?");
    $stmt->bind_param("s", $county); // Binds the county variable to the parameter in the SQL query
    $stmt->execute(); // Executes the SQL query
    $result = $stmt->get_result(); // Gets the result of the query
    $companies = $result->fetch_all(MYSQLI_ASSOC); // Fetches all rows from the result as an associative array
    
    echo json_encode($companies); // Encodes the companies array as JSON and returns it

    $stmt->close(); // Closes the prepared statement
    $conn->close(); // Closes the database connection
} else {
    echo json_encode(['error' => 'No county provided']); // Returns an error if no county was provided
}
?>
