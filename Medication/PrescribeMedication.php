<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../security.php';
curaSecurity('doctor');
// Database connection
$host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "cura";

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name,8889);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if doctor is logged in
if (!isset($_SESSION['DOCTOR_ID'])) {
    header("Location: ../Login/login.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

// Get patient_id and appointment_id from query string
if (!isset($_GET['patient_id']) || !isset($_GET['appointment_id'])) {
    die("Missing required data.");
}

$patient_id = intval($_GET['patient_id']);
$appointment_id = intval($_GET['appointment_id']);

// Retrieve patient info
$sql_patient = "SELECT firstName, lastName, DoB, Gender FROM Patient WHERE id = $patient_id";
$result_patient = mysqli_query($conn, $sql_patient);

if (!$result_patient || mysqli_num_rows($result_patient) == 0) {
    die("Patient not found.");
}

$patient = mysqli_fetch_assoc($result_patient);

// Helper function to calculate age
function calculateAge($dob) {
    $birthDate = new DateTime($dob);
    $today = new DateTime("today");
    return $birthDate->diff($today)->y;
}

$patient_age = calculateAge($patient['DoB']);

// Get available medications
$sql_meds = "SELECT id, MedicationName FROM Medication";
$result_meds = mysqli_query($conn, $sql_meds);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - CURA - Presribe Medication</title>
    <link rel="stylesheet" href="Medication.css">
    <link rel="stylesheet" href="../Main.css">
</head>
<body>

<nav class="navbar" style="box-shadow: 0 5px 6px rgba(0, 0, 0, 0.1);">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <a href="../Home/Home.php" class="logo">
            <span>CURA</span>
        </a>
      
    </div>
</nav>

    <section class="medication-section">
        <div class="medication-container">
                        <div class="info-box">
            </div>
            <div class="form-box">
                <h1>Prescribe Medication</h1>

                <form action="SubmitPrescription.php" method="POST">
                    <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                    <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="patient-name">Patient's Name</label>
                            <input type="text" id="patient-name" value="<?php echo $patient['firstName'] . ' ' . $patient['lastName']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="patient-age">Age</label>
                            <input type="number" id="patient-age" value="<?php echo $patient_age; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="gender" value="male" <?php if ($patient['Gender'] === 'Male') echo 'checked'; ?> disabled> Male
                            </label>
                            <label>
                                <input type="radio" name="gender" value="female" <?php if ($patient['Gender'] === 'Female') echo 'checked'; ?> disabled> Female
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Select Medications</label>
                        <div class="checkbox-group">
                            <?php if (mysqli_num_rows($result_meds) > 0): ?>
                                <?php while ($med = mysqli_fetch_assoc($result_meds)): ?>
                                    <label>
                                        <input type="checkbox" name="medications[]" value="<?php echo $med['id']; ?>">
                                        <?php echo $med['MedicationName']; ?>
                                    </label>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No medications available.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="prebtn">Submit</button>
                </form>

            </div>
        </div>
    </section>

  
<footer class="footer">
    <div class="container">
        <p>&copy; 2025 CURA | All Rights Reserved</p>
        <p class="contact-info">
            <strong>Address:</strong> 7720 Riyadh, Laysen Valley, 44321<br>
            <strong>Phone:</strong> (966) 556-789-5900
        </p>
        <ul class="social-links">
            <li><img src="../images/twitter.webp" alt="Twitter"></li>
            <li><img src="../images/free-instagram-logo-icon-3497-thumb.png" alt="Instagram"></li>
            <li><img src="../images/Email542689.png" alt="Email"></li>
        </ul>
    </div>
</footer>

</body>
</html>
<?php
// Close MySQL connection
mysqli_close($conn);
?>