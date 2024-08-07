<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
require 'db_connection.php';  // Ensure you have your database connection here

header('Content-Type: application/json');

// Get the authorization header
$headers = apache_request_headers();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

list($jwt) = sscanf($authHeader, 'Bearer %s');

if ($jwt) {
    try {
        $key = "abcd123";
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        
        $userId = $decoded->sub;

        // Retrieve user details from the database
        $query = $conn->prepare("SELECT username,role FROM users WHERE id = ?");
        $query->bind_param('i', $userId);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit();
        }

        $user = $result->fetch_assoc();
        echo json_encode(['success' => true, 'user' => $user]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid token']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Token not provided']);
}
?>