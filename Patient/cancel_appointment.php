<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$database = "cura"; 

// Connect using procedural MySQLi
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['patient_ID']) || !isset($_GET['id'])) {
    header("Location: ../Patient/patient.php");
    exit();
}

$appointment_id = $_GET['id'];
$patient_id = $_SESSION['patient_ID'];

// Prepare and bind (procedural)
$stmt = mysqli_prepare($conn, "DELETE FROM appointment WHERE ID = ? AND PatientID = ?");
mysqli_stmt_bind_param($stmt, "ii", $appointment_id, $patient_id);
mysqli_stmt_execute($stmt);

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Redirect
header("Location: ../Patient/patient.php");
exit();
?>
