<?php
require 'db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php'; // Include Composer's autoloader

header('Content-Type: application/json');

// Read the raw input data
$rawData = file_get_contents('php://input');

// Decode the JSON input
$data = json_decode($rawData, true);

// Extract fields from the data array
$firstName = $data['firstName'] ?? null;
$lastName = $data['lastName'] ?? null;
$username = $data['username'] ?? null;
$email = $data['email'] ?? null;
$branch = $data['branch'] ?? null;
$password = $data['password'] ?? null;
$confirmPassword = $data['confirmPassword'] ?? null;

// Validate required fields
if (!$username || !$password || !$confirmPassword || !$firstName || !$lastName) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

// Check if the username already exists
$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param('s', $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already exists']);
    exit();
}

// Check if passwords match
if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit();
}

// Hash the password before storing it
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user into the database
$query = $conn->prepare("INSERT INTO users (username, email, branch, password, first_name, last_name) VALUES (?, ?, ?, ?, ?, ?)");
$query->bind_param('ssssss', $username, $email, $branch, $hashed_password, $firstName, $lastName);

if ($query->execute()) {
    // Get user ID
    $userId = $query->insert_id;

    // Send confirmation email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'vg2556519@gmail.com'; // Your Gmail email address
        $mail->Password = 'cuyu vljo enge rgmp'; // Your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Your Company Name'); // Your Gmail email address
        $mail->addAddress($email); // Recipient's email address

        // Content
        $mail->isHTML(false);
        $mail->Subject = "Registration Confirmation";
        $mail->Body = "Welcome to the Publication System!\n\nWe are delighted to have you as part of our community and look forward to receiving your valuable contributions.\n\nYour registration details are as follows:\n\nUser ID: $userId\nEmail: $email\nPassword: $password\n\nThank you for registering!\n\n";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Registration successful. Confirmation email sent.']);
    } catch (Exception $e) {
        echo json_encode(['success' => true, 'message' => 'Registration successful, but there was an issue sending the confirmation email: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Signup failed']);
}
?>
