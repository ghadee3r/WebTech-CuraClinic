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
        echo $success ? "true" : "false"; // ✅ RIGHT HERE
    } else {
        echo "false";
    }

} else {
    echo "false";
}

mysqli_close($conn);
?>
