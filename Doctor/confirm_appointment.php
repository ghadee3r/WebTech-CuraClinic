<?php
session_start();
include '../security.php';
curaSecurity('doctor');

// Database connection
$host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "cura";

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name, '8889');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if doctor is logged in
if (!isset($_SESSION['DOCTOR_ID'])) {
    header("Location: ../Login/logIn.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

// Get the appointment_id from the URL
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Update appointment status to Confirmed
    $sql = "
        UPDATE Appointment
        SET status = 'Confirmed'
        WHERE id = $appointment_id
    ";

    if (mysqli_query($conn, $sql)) {
        // Success - redirect back to homepage
        header("Location: doctor.php");
        exit();
    } else {
        echo "Error confirming appointment.";
    }
} else {
    echo "No appointment selected.";
}
?>
