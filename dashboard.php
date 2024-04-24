<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Redirect if not logged in
if (empty($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

require_once 'dbconnection.php';
$conn = dbconnect();

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['userID']; // Assumed to be retrieved and sanitized earlier
    $productTitle = filter_input(INPUT_POST, 'productTitle', FILTER_SANITIZE_STRING);
    $productPrice = filter_input(INPUT_POST, 'productPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $productCategory = filter_input(INPUT_POST, 'productCategory', FILTER_SANITIZE_STRING);

    // Prepare an SQL statement to insert the product data
    $stmt = $conn->prepare("INSERT INTO Products (UserID, ProductTitle, ProductPrice, ProductCategory) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $userID, $productTitle, $productPrice, $productCategory);

    // Execute the prepared statement
    if (!$stmt->execute()) {
        // Handle any errors that occur during insert
        echo "<script>alert('Error adding product: " . addslashes($stmt->error) . "');</script>";
    } else {
        // Redirect or notify of success
        echo "<script>alert('Product added successfully.');</script>";
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="./style/nav.css" />
  <link rel="stylesheet" href="./style/dash.css" />
  <script src="./js/nav.js" defer></script>
</head>
<body>
  <div id="nav-placeholder"></div>
  
  <!-- Banner Section -->  
  <header class="banner">Banner</header>
  
  <div class="container">
    <!-- Sidebar Section -->
    <section class="sidebar">
        <form id="company-form" method="post" action="submit-company-details.php">
            <div class="company-logo">
                LOGO
            </div>
            <div class="form-group">
                <label for="company-name">Company Name:</label>
                <input type="text" id="company-name" name="companyName" required>
            </div>
            <div class="form-group">
                <label for="company-location">Location:</label>
                <select id="company-location" name="companyLocation" required>
                    <option value="Carlow">Carlow</option>
                    <option value="Cavan">Cavan</option>
                    <option value="Clare">Clare</option>
                    <option value="Cork">Cork</option>
                    <option value="Donegal">Donegal</option>
                    <option value="Dublin">Dublin</option>
                    <option value="Galway">Galway</option>
                    <option value="Kerry">Kerry</option>
                    <option value="Kildare">Kildare</option>
                    <option value="Kilkenny">Kilkenny</option>
                    <option value="Laois">Laois</option>
                    <option value="Leitrim">Leitrim</option>
                    <option value="Limerick">Limerick</option>
                    <option value="Longford">Longford</option>
                    <option value="Louth">Louth</option>
                    <option value="Mayo">Mayo</option>
                    <option value="Meath">Meath</option>
                    <option value="Monaghan">Monaghan</option>
                    <option value="Offaly">Offaly</option>
                    <option value="Roscommon">Roscommon</option>
                    <option value="Sligo">Sligo</option>
                    <option value="Tipperary">Tipperary</option>
                    <option value="Waterford">Waterford</option>
                    <option value="Westmeath">Westmeath</option>
                    <option value="Wexford">Wexford</option>
                    <option value="Wicklow">Wicklow</option>
                </select>
            </div>
            <div class="form-group">
                <label for="company-contact">Contact Info:</label>
                <input type="text" id="company-contact" name="companyContact" required>
            </div>
            <div class="form-group">
                <button type="submit" id="submit-company">Submit</button>
            </div>
        </form>
    </section>

    <!-- Product Form Section -->
    <section class="product-form">
      <h2>Add New Product</h2>
      <form action="dashboard.php" method="post">
        <div class="form-group">
          <label for="productTitle">Product Title:</label>
          <input type="text" id="productTitle" name="productTitle" required>
        </div>
        
        <div class="form-group">
          <label for="productPrice">Product Price:</label>
          <input type="number" id="productPrice" name="productPrice" step="0.01" required>
        </div>
        
        <div class="form-group">
          <label for="productCategory">Product Category:</label>
          <select id="productCategory" name="productCategory" required>
              <option value="cat1">Restaurants & Bars</option>
              <option value="cat2">Hotels</option>
              <option value="cat3">Fashion</option>
              <option value="cat4">Health & Beauty</option>
              <option value="cat5">Food & Groceries</option>
              <option value="cat6">Toys & Games</option>
          </select>
        </div>
        <div class="form-group">
          <button type="submit">Add Product</button>
        <div class="form-group">
          
      </form>
    </section>
  </div>
      
  <div class="container">
    <section class="company-details-container"></section>
    
    <section id="product-list" class="product-list">
      <h2>Your Products</h2>
      <div id="products-container">
      
      </div>
      <button id="refresh-products" onclick="loadProducts()">Refresh Products</button>
    </section>
  </div>

  
  <script src="./js/dash.js" defer></script>
</body>
</html>