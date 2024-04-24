<?php
    include('dbconnection.php');
    $conn = dbconnect();

    // Initialize response array
    $response = [
        'status' => 'error',
        'message' => 'An error occurred',
    ];

    // Check connection
    if ($conn->connect_error) {
        $response['message'] = 'Connection issue';
        echo json_encode($response);
        exit();
    }       

    if(isset($_GET['name'])) {
        $uname = $conn->real_escape_string($_GET['name']);
        $stmt = $conn->prepare("SELECT fname, lname FROM users WHERE username = ?");
        $stmt->bind_param('s', $uname);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = [
                'status' => 'success',
                'fname' => $row['fname'],
                'lname' => $row['lname']
            ];
        } else {
            $response['message'] = 'User not found';
        }
    } else {
        $response['message'] = 'Name not provided';
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
?>
