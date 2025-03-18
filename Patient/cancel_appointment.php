<?php
session_start();
$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$database = "cura"; 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!isset($_SESSION['patient_id']) || !isset($_GET['id'])) {
    header("Location: patient_dashboard.php");
    exit();
}

$appointment_id = intval($_GET['id']);
$patient_id = $_SESSION['patient_id'];

// Delete appointment only if it's pending
$query_delete = "DELETE FROM appointments WHERE id = $appointment_id AND patient_id = $patient_id AND status = 'pending'";
mysqli_query($conn, $query_delete);

header("Location: patient_dashboard.php");
exit();
?>

