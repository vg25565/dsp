<?php
require 'auth.php'; // Include this on top to protect the route
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="faculty.css">
</head>
<body class="bg-gray-100">
    <!-- Navbar and Main Content as in your HTML code -->
    <!-- Form for Uploading Documents -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Upload Document</h2>
        <form action="upload_document.php" method="post" enctype="multipart/form-data" id="uploadForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="title" class="block text-gray-700 mb-1">Title</label>
                    <input type="text" id="title" name="title" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>
                <div>
                    <label for="month" class="block text-gray-700 mb-1">Month</label>
                    <select id="month" name="month" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div>
                    <label for="year" class="block text-gray-700 mb-1">Year</label>
                    <input type="text" id="year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded" required disabled>
                </div>
                <div class="md:col-span-2">
                    <label for="fileUpload" class="block text-gray-700 mb-1">Upload Document (PDF only)</label>
                    <input type="file" id="fileUpload" name="fileUpload" accept=".pdf" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currentYear = new Date().getFullYear();
            document.getElementById('year').value = currentYear;
        });
    </script>
</body>
</html>
