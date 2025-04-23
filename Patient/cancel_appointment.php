<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$database = "cura"; 

// Connect using procedural MySQLi
$conn = mysqli_connect($servername, $username, $password, $database,8889);

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























<?php 
session_start();
include '../security.php';
curaSecurity('patient');

header("Content-Type: text/plain");

$conn = mysqli_connect("localhost", "root", "root", "cura");
if (!$conn) {
    echo "false";
    exit;
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Optional: Only allow canceling appointments that belong to the current patient
    $patient_id = $_SESSION['patient_ID'];
    $check_query = "SELECT * FROM appointment WHERE ID = ? AND PatientID = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "ii", $id, $patient_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $sql = "DELETE FROM appointment WHERE ID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        echo $success ? "true" : "false"; 
    } else {
        echo "false";
    }

} else {
    echo "false";
}

mysqli_close($conn);

