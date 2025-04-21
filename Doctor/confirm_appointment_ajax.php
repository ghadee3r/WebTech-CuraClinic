<?php
session_start();
header("Content-Type: application/json"); // Important for AJAX

include '../security.php';
curaSecurity('doctor');

$conn = mysqli_connect('localhost', 'root', 'root', 'cura');
if (!$conn) {
    echo json_encode(false);
    exit;
}

if (isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $sql = "UPDATE Appointment SET status = 'Confirmed' WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $appointment_id);
    $success = mysqli_stmt_execute($stmt);

    echo json_encode($success);
} else {
    echo json_encode(false);
}

mysqli_close($conn);
?>
