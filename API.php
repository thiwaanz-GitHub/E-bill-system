<?php
include 'includes/connection.php';

// Define API endpoint and request method
$endpoint = $_GET['/customer/{id}'];
$method = $_SERVER['REQUEST_METHOD'];

// Check if the endpoint exists and handle request
if ($endpoint === 'users') {
    if ($method === 'GET') {
        // Retrieve user data from database
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            // Format response data as JSON
            header('Content-Type: application/json');
            echo json_encode($users);
        } else {
            echo "No users found";
        }
    } else {
        // Method not allowed
        header("HTTP/1.0 405 Method Not Allowed");
        echo "Method not allowed";
    }
} else {
    // Endpoint not found
    header("HTTP/1.0 404 Not Found");
    echo "Endpoint not found";
}

// Close database connection
$conn->close();
?>
