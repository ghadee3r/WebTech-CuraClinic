<?php
session_start();

// Database connection
$host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "cura";

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name,'8889');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['DOCTOR_ID'])) {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

// Get patient ID from POST
$patient_id = $_POST['patient_ID'];

// Medications selected
$medications = isset($_POST['medications']) ? $_POST['medications'] : [];

if (empty($medications)) {
    die("No medications selected.");
}

// Find the latest confirmed appointment for this doctor and patient
$sql_appointment = "
    SELECT id FROM Appointment
    WHERE DoctorID = $doctor_id AND PatientID = $patient_id AND status = 'Confirmed'
    ORDER BY date DESC, time DESC
    LIMIT 1
";

$result_appointment = mysqli_query($conn, $sql_appointment);

if (!$result_appointment || mysqli_num_rows($result_appointment) == 0) {
    die("No confirmed appointment found.");
}

$appointment = mysqli_fetch_assoc($result_appointment);
$appointment_id = $appointment['id'];

// 1. Update appointment status to Done
$update_status = "
    UPDATE Appointment
    SET status = 'Done'
    WHERE id = $appointment_id
";

if (!mysqli_query($conn, $update_status)) {
    die("Failed to update appointment status.");
}

// 2. Insert prescriptions
foreach ($medications as $medication_id) {
    $insert_prescription = "
        INSERT INTO Prescription (AppointmentID, MedicationID)
        VALUES ($appointment_id, $medication_id)
    ";

    if (!mysqli_query($conn, $insert_prescription)) {
        die("Failed to insert prescription for medication ID $medication_id");
    }
}

// 3. Redirect to doctor homepage
header("Location: doctor_homepage.php");
exit();
?>
