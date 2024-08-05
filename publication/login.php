<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
require 'db_connection.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'];
$password = $data['password'];

$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param('s', $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    exit();
}

$user = $result->fetch_assoc();

if ($password == $user['password']) { // This is a simple example; in a real application, use hashed passwords.
    $key = "abcd123";
    $payload = [
        'iss' => 'localhost',
        'aud' => 'localhost',
        'iat' => time(),
        'exp' => time() + (60 * 60),
        'sub' => $user['id'],
        'role' => $user['role']
    ];

    $jwt = JWT::encode($payload, $key, 'HS256');
    echo json_encode(['success' => true, 'token' => $jwt, 'role' => $user['role']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}
?>
