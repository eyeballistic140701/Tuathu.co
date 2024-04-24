<?php
  function dbconnect(){
    $host = '141.136.33.1';
    $username = 'u672629955_tuathu';
    $password = 'Tuathu@1234';
    $database = 'u672629955_tuathu';
    
    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    return $conn;
  }
?>