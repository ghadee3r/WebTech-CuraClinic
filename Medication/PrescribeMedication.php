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

// Check if doctor is logged in
if (!isset($_SESSION['DOCTOR_ID'])) {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

// Check for patient_id in query string
if (!isset($_GET['patient_ID'])) {
    die("No patient selected.");
}

$patient_id = $_GET['patient_ID'];

// Get patient info
$sql_patient = "SELECT firstName, lastName, DoB, Gender FROM Patient WHERE id = $patient_id";
$result_patient = mysqli_query($conn, $sql_patient);

if (!$result_patient || mysqli_num_rows($result_patient) == 0) {
    die("Patient not found.");
}

$patient = mysqli_fetch_assoc($result_patient);

// Calculate age
function calculateAge($dob) {
    $birthDate = new DateTime($dob);
    $today = new DateTime("today");
    return $birthDate->diff($today)->y;
}

$patient_age = calculateAge($patient['DoB']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - Prescribe Medication</title>
   
    <link rel="stylesheet" href="Main.css">
    <link rel="stylesheet" href="Medication.css">
</head>

<body>
    
    <nav class="navbar">
        <div class="container">
            <a href="Home.html" class="logo">
                <span>CURA</span>
            </a>
        </div>
    </nav>
   
    <section class="medication-section">
        <div class="medication-container">
            <div class="info-box">
                <!-- Optional info box content -->
            </div>

            <div class="form-box">
                <h1>Patient's Medications</h1>

                <form action="SubmitPrescription.php" method="POST">
                    <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="patient-name">Patient's Name</label>
                            <input type="text" id="patient-name" name="patient_name" value="<?php echo $patient['firstName'] . ' ' . $patient['lastName']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="patient-age">Age</label>
                            <input type="number" id="patient-age" name="age" value="<?php echo $patient_age; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="gender" value="male" <?php if($patient['Gender'] === 'M') echo 'checked'; ?> disabled> Male
                            </label>
                            <label>
                                <input type="radio" name="gender" value="female" <?php if($patient['Gender'] === 'F') echo 'checked'; ?> disabled> Female
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Medications</label>
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" name="medications[]" value="1"> Prozac
                            </label>
                            <label>
                                <input type="checkbox" name="medications[]" value="2"> Sertraline
                            </label>
                            <label>
                                <input type="checkbox" name="medications[]" value="3"> Lamotrigine
                            </label>
                            <label>
                                <input type="checkbox" name="medications[]" value="4"> Duloxetine
                            </label>
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
                <li><img src="images/twitter.webp" alt="Twitter"></li>
                <li><img src="images/free-instagram-logo-icon-3497-thumb.png" alt="Instagram"></li>
                <li><img src="images/Email542689.png" alt="Email"></li>
            </ul>
        </div>
    </footer>

</body>
</html>
