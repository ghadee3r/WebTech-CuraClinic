<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "cura";

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name, '8889');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['DOCTOR_ID'])) {
    header("Location: ../Login/login.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

$patient_id = intval($_POST['patient_id']);
$appointment_id = intval($_POST['appointment_id']);
$medications = $_POST['medications'] ?? [];

if (!$patient_id || !$appointment_id || empty($medications)) {
    die("Missing data. Make sure you selected medications.");
}

$sql_check = "
    SELECT id FROM Appointment
    WHERE id = $appointment_id AND DoctorID = $doctor_id AND PatientID = $patient_id
";

$result_check = mysqli_query($conn, $sql_check);
if (!$result_check || mysqli_num_rows($result_check) == 0) {
    die("Unauthorized operation.");
}

$sql_update_appt = "
    UPDATE Appointment
    SET status = 'Done'
    WHERE id = $appointment_id
";
mysqli_query($conn, $sql_update_appt);

foreach ($medications as $med_id) {
    $med_id = intval($med_id);

    $sql_insert_presc = "
        INSERT INTO Prescription (AppointmentID, MedicationID)
        VALUES ($appointment_id, $med_id)
    ";
    mysqli_query($conn, $sql_insert_presc);
}

header("Location: ../Doctor/doctor.php");
exit();


// Close MySQL connection
mysqli_close($conn);
?>