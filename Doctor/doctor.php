<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection (procedural)
$host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "cura";

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name,'8889');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the doctor is logged in
if (!isset($_SESSION['DOCTOR_ID'])) {
    header("Location: ../Login/logIn.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

// Get doctor info
$sql_doctor = "
    SELECT d.firstName, d.lastName, d.uniqueFileName, s.speciality, d.emailAddress
    FROM Doctor d
    INNER JOIN Speciality s ON d.SpecialityID = s.id
    WHERE d.id = $doctor_id
";


$result_doctor = mysqli_query($conn, $sql_doctor);
$doctor = mysqli_fetch_assoc($result_doctor);

// Get upcoming appointments (Pending or Confirmed)
$sql_appointments = "
    SELECT a.id AS appointment_id, p.id AS PatientID, p.firstName, p.lastName, p.DoB, p.Gender, a.reason, a.date, a.time, a.status
    FROM Appointment a
    INNER JOIN Patient p ON a.PatientID = p.id
    WHERE a.DoctorID = $doctor_id AND (a.status = 'Pending' OR a.status = 'Confirmed')
    ORDER BY a.date, a.time
";


$result_appts = mysqli_query($conn, $sql_appointments);

// Get patients who had Done appointments
$sql_patients = "
    SELECT DISTINCT p.id, p.firstName, p.lastName, p.DoB, p.Gender
    FROM Appointment a
    INNER JOIN Patient p ON a.PatientID = p.id
    WHERE a.DoctorID = $doctor_id AND a.status = 'Done'
    ORDER BY p.lastName ASC
";

$result_patients = mysqli_query($conn, $sql_patients);

// Helper function to calculate age
function calculateAge($dob) {
    $birthDate = new DateTime($dob);
    $today = new DateTime("today");
    return $birthDate->diff($today)->y;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - Doctor Homepage</title>
    <link rel="stylesheet" href="doctor.css">
    <link rel="stylesheet" href="../Main.css">
</head>
<body>

<nav class="navbar" style="box-shadow: 0 5px 6px rgba(0, 0, 0, 0.1);">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <a href="../Home/Home.php" class="logo">
            <span>CURA</span>
        </a>
        <a href="../logout.php" class="signout-btn" style="margin-left: auto;">Sign Out</a>
    </div>
</nav>

<section class="welcome-section">
    <div class="welcome-left">
        <p>Welcome to CURA,</p> 
        <h2>Dr. <?php echo $doctor['firstName'] . ' ' . $doctor['lastName']; ?>!</h2>
    </div>
    <div class="doctor-profile">
        <img src="../DBimages/<?php echo $doctor['uniqueFileName']; ?>" alt="Doctor Photo" class="profile-pic">
        <p><strong>Name:</strong> Dr. <?php echo $doctor['firstName'] . ' ' . $doctor['lastName']; ?></p>
        <p><strong>ID:</strong> <?php echo $doctor_id; ?></p>
        <p><strong>Email:</strong> <?php echo $doctor['emailAddress']; ?></p>
        <p><strong>Specialty:</strong> <?php echo $doctor['speciality']; ?></p>
    </div>
</section>

<section class="dashboard">

    <!-- Upcoming Appointments Section -->
    <div class="appointments">
        <h2>Upcoming Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result_appts) > 0): ?>
                <?php while ($appt = mysqli_fetch_assoc($result_appts)): ?>
                    <tr>
                        <td><?php echo $appt['firstName'] . ' ' . $appt['lastName']; ?></td>
                        <td><?php echo calculateAge($appt['DoB']); ?></td>
                        <td><?php echo $appt['Gender']; ?></td>
                        <td><?php echo $appt['reason']; ?></td>
                        <td><?php echo $appt['date']; ?></td>
                        <td><?php echo $appt['time']; ?></td>
                        <td>
                            <?php if ($appt['status'] === 'Pending'): ?>
                                <a href="confirm_appointment.php?appointment_id=<?php echo $appt['appointment_id']; ?>" class="confirm-btn">Confirm</a>
                            <?php elseif ($appt['status'] === 'Confirmed'): ?>
                                <a href="../Medication/PrescribeMedication.php?patient_id=<?php echo $appt['PatientID']; ?>&appointment_id=<?php echo $appt['appointment_id']; ?>" class="prescribe-btn">Prescribe</a>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No upcoming appointments.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Patients Section -->
    <div class="patients">
        <h2>Patients</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Medications</th>

                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result_patients) > 0): ?>
                <?php while ($patient = mysqli_fetch_assoc($result_patients)): ?>
                    <?php
                    $patient_id = $patient['id'];
                    $sql_meds = "
                        SELECT DISTINCT m.MedicationName
                        FROM Prescription pr
                        INNER JOIN Medication m ON pr.MedicationID = m.id
                        INNER JOIN Appointment a ON pr.AppointmentID = a.id
                        WHERE a.PatientID = $patient_id AND a.DoctorID = $doctor_id
                    ";

                    $result_meds = mysqli_query($conn, $sql_meds);
                    $medications = [];

                    if (mysqli_num_rows($result_meds) > 0) {
                        while ($med = mysqli_fetch_assoc($result_meds)) {
                            $medications[] = $med['MedicationName'];
                        }
                    }
                    ?>
                    <tr>
                        <td><?php echo $patient['firstName'] . ' ' . $patient['lastName']; ?></td>
                        <td><?php echo calculateAge($patient['DoB']); ?></td>
                        <td><?php echo $patient['Gender']; ?></td>
                        <td><?php echo !empty($medications) ? implode(", ", $medications) : '-'; ?></td>
                       
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No patients found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
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
