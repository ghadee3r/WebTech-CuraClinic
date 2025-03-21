<?php
session_start();

function curaSecurity($requiredUserType) {
    // Check if user is logged in
    if (!isset($_SESSION['USER_TYPE'])) {
        // Not logged in, redirect to home (or login)
        header("Location: ../Home/Home.php");
        exit();
    }
 
    if ($requiredUserType === 'doctor' && !isset($_SESSION['DOCTOR_ID'])) {
        header("Location: ../Home/Home.php");
        exit();
    }
    if ($requiredUserType === 'patient' && !isset($_SESSION['patient_ID'])) {
        header("Location: ../Home/Home.php");
        exit();
    }
}
?>
