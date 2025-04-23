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

if (isset($_POST['id']) && isset($_SESSION['patient_ID'])) {
    $id = intval($_POST['id']);
    $patient_id = $_SESSION['patient_ID'];

    $sql = "DELETE FROM appointment WHERE ID = ? AND PatientID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $patient_id);
    $success = mysqli_stmt_execute($stmt);

    echo $success ? "true" : "false";
} else {
    echo "false";
}

mysqli_close($conn);
