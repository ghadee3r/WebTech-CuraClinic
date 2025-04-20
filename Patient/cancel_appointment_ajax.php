<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$database = "cura";

$conn = mysqli_connect($servername, $username, $password, $database,8889);
if (!$conn) {
    echo "false";
    exit();
}

if (!isset($_SESSION['patient_ID']) || !isset($_POST['id'])) {
    echo "false";
    exit();
}

$appointment_id = intval($_POST['id']);
$patient_id = $_SESSION['patient_ID'];

$query = "SELECT * FROM appointment WHERE ID = $appointment_id AND PatientID = $patient_id AND status != 'Done'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
    $delete_query = "DELETE FROM appointment WHERE ID = $appointment_id AND PatientID = $patient_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "true";
    } else {
        echo "false";
    }
} else {
    echo "false";
}

mysqli_close($conn);
?>
