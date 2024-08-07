<?php
session_start(); // Ensure this is called at the beginning
require 'db_connection.php';

// Check if the user is logged in or newly signed up
$user_id = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (isset($_SESSION['new_user_id'])) {
    $user_id = $_SESSION['new_user_id']; // Allow newly signed up users
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the form
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $month = isset($_POST['month']) ? $_POST['month'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';

    // Check if a file was uploaded
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['fileUpload']['tmp_name'];
        $fileName = $_FILES['fileUpload']['name'];
        $fileSize = $_FILES['fileUpload']['size'];
        $fileType = $_FILES['fileUpload']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Check file extension
        $allowedExtensions = ['pdf'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Define the new file name and path
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploaded_files/';
            $dest_path = $uploadFileDir . $newFileName;

            // Move the file to the destination directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Insert file details into the database
                $query = $conn->prepare("INSERT INTO documents (title, month, year, file_name, user_id) VALUES (?, ?, ?, ?, ?)");
                $query->bind_param('ssssi', $title, $month, $year, $newFileName, $user_id);
                if ($query->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Document uploaded successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to save document details']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
