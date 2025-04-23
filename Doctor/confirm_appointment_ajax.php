<?php
session_start();
header("Content-Type: application/json"); // Important for AJAX response

include '../security.php';
curaSecurity('doctor'); // Will return proper JSON if session is invalid

$conn = mysqli_connect('localhost', 'root', 'root', 'cura');
if (!$conn) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

if (isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);

    $sql = "UPDATE Appointment SET status = 'Confirmed' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $appointment_id);
        $success = mysqli_stmt_execute($stmt);
        echo json_encode(["success" => $success]);
    } else {
        echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Missing appointment_id"]);
}

mysqli_close($conn);
