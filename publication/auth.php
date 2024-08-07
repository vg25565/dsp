<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html'); // Redirect to login page if not authenticated
    exit();
}
?>
