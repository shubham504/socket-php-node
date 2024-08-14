<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "chat_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM messages ORDER BY timestamp DESC"; // Order messages by ascending timestamp
    $result = $conn->query($sql);

    $messages = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    echo json_encode($messages); // Return messages as JSON
}

// Save message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (user, message) VALUES ('$user', '$message')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
