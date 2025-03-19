<?php


// Database connection
$con = mysqli_connect('localhost', 'root', 'root', 'cura', '8889');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$folderPath = 'DBimages/';
$files = scandir($folderPath);

// Allowed image file extensions
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

echo "<h3>Renamed Files:</h3>";

foreach ($files as $file) {

    // Skip system entries
    if ($file === '.' || $file === '..') {
        continue;
    }

    // Get file extension
    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    // Process only images
    if (in_array($fileExtension, $allowedExtensions)) {

        // Generate a unique ID for the file name
        $newFileName = uniqid('img_', true) . '.' . $fileExtension;

        // Full old and new file paths
        $oldPath = $folderPath . $file;
        $newPath = $folderPath . $newFileName;

        // Rename the file
        if (rename($oldPath, $newPath)) {
            echo "$file renamed to $newFileName<br>";
        } else {
            echo "Failed to rename $file<br>";
        }
    } else {
        echo "Skipping unsupported file: $file<br>";
    }
}
?>
