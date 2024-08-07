<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate input
    if (empty($userId) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please enter both User ID and Password']);
        exit();
    }

    // Prepare and execute the query
    $query = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $query->bind_param('s', $userId);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid User ID or Password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
