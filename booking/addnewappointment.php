<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$con = mysqli_connect('localhost', 'root', 'root', 'cura');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$patientID = $_SESSION['patient_ID'];

$doctorID = $_POST['hiddenDoctorID'];
$date = $_POST['date'];
$time = $_POST['time'];
$reason = $_POST['reason'];

// Insert appointment with Pending status
$sql = "INSERT INTO appointment (DoctorID, PatientID, date, time, reason, status)
        VALUES ('$doctorID', '$patientID', '$date', '$time', '$reason', 'Pending')";

if (mysqli_query($con, $sql)) {
    // Set the message in session (invisible in URL)
    $_SESSION['appointment_message'] = "Appointment requested successfully";
    
    // Redirect to patient's home page
    header("Location: ../Patient/patient.php");
    exit();
} else {
    echo "Error: " . mysqli_error($con);
}

// Close MySQL connection
mysqli_close($con);
?>
