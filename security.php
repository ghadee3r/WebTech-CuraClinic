<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Enforces role-based access.
 * 
 * @param string $requiredRole 'doctor' or 'patient'
 */
function curaSecurity($requiredRole) {
    // Check if role session is missing or mismatched
    if (!isset($_SESSION['USER_TYPE']) || $_SESSION['USER_TYPE'] !== $requiredRole) {
        // Clear session just in case
        session_unset();
        session_destroy();
        // Redirect to login or home
        header("Location: ../Home/Home.php");
        exit();
    }

    // Additionally check that the corresponding ID is set
    if ($requiredRole === 'doctor' && !isset($_SESSION['DOCTOR_ID'])) {
        header("Location: ../Login/logIn.php");
        exit();
    }

    if ($requiredRole === 'patient' && !isset($_SESSION['patient_ID'])) {
        header("Location: ../Login/logIn.php");
        exit();
    }
}
