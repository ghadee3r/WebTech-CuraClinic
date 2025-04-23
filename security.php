<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks if request is via AJAX
 */
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Enforces role-based access.
 * 
 * @param string $requiredRole 'doctor' or 'patient'
 */
function curaSecurity($requiredRole) {
    if (!isset($_SESSION['USER_TYPE']) || $_SESSION['USER_TYPE'] !== $requiredRole) {
        session_unset();
        session_destroy();

        if (is_ajax()) {
            header("Content-Type: application/json");
            echo json_encode(["error" => "unauthorized"]);
        } else {
            header("Location: ../Home/Home.php");
        }
        exit();
    }

    if ($requiredRole === 'doctor' && !isset($_SESSION['DOCTOR_ID'])) {
        if (is_ajax()) {
            header("Content-Type: application/json");
            echo json_encode(["error" => "doctor_id_missing"]);
        } else {
            header("Location: ../Login/logIn.php");
        }
        exit();
    }

    if ($requiredRole === 'patient' && !isset($_SESSION['patient_ID'])) {
        if (is_ajax()) {
            header("Content-Type: application/json");
            echo json_encode(["error" => "patient_id_missing"]);
        } else {
            header("Location: ../Login/logIn.php");
        }
        exit();
    }
}
