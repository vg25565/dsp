<?php
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-white text-2xl font-bold">Faculty Dashboard</div>
            <div class="lg:flex space-x-4">
                <a href="index.html" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Home</a>
                <a href="index.html" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Task</a>
                <a href="account.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Account</a>
                <a href="#" id="login-signup-button" class="text-white hover:bg-blue-700 px-3 py-2 rounded">Login/Signup</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Account Details</h2>
            <?php if ($user): ?>
                <p class="text-gray-700"><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                <p class="text-gray-700"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p class="text-gray-700"><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <?php else: ?>
                <p class="text-gray-700">No account details available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
