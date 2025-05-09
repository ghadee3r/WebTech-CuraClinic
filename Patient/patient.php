<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../security.php';
curaSecurity('patient');

// Check if there is a message to show
if (isset($_SESSION['appointment_message'])) {
    $message = $_SESSION['appointment_message'];
    echo "<p style='color: green; text-align:center;'>$message</p>";

    // Unset the message so it doesn't show again on refresh
    unset($_SESSION['appointment_message']);
} // <<< FIX: Closing the if block here

$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$database = "cura"; 


// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure the patient is logged in
if (!isset($_SESSION['patient_ID'])) { 
    header("Location: ../login.php");
    exit();
}

$patient_id = $_SESSION['patient_ID'];

// Fetch patient details
$query_patient = "SELECT firstName, lastName, emailAddress, Gender, DoB FROM patient WHERE ID = $patient_id";
$result_patient = mysqli_query($conn, $query_patient);
$patient = mysqli_fetch_assoc($result_patient);

// Fetch patient appointments with doctor details
$query_appointments = "
    SELECT a.ID, a.date, a.time, a.status, 
           CONCAT(d.firstName, ' ', d.lastName) AS doctor_name, 
           d.uniqueFileName AS doctor_photo
    FROM appointment a
    JOIN doctor d ON a.DoctorID = d.ID
    WHERE a.PatientID = $patient_id AND a.status != 'Done'
    ORDER BY a.date, a.time";

$result_appointments = mysqli_query($conn, $query_appointments);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - Patient Homepage</title>
    <link rel="stylesheet" href="../Main.css">
    <link rel="stylesheet" href="Patient.css">
</head>

<body>
    <nav class="navbar" style="box-shadow: 0 5px 6px rgba(0, 0, 0, 0.1);">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <a href="../Home/Home.php" class="logo">
                <span>CURA</span>
            </a>
        </div>
        <a href="../LogOut/LogOut.php" id="patientSignOut" style="margin-left: auto;">Sign Out</a>
    </nav>

    <section id="patientWelcome">
        <div id="patientWelcomeLeft">
            <h2>Welcome, <?php echo $patient['firstName'] ?>!</h2>
            <p>Your journey to wellness starts here.</p> 
        </div>
        
        <div id="patientInfoContainer">
            <div id="patientInfo">
                <br><span id="patientName"><?php echo $patient['firstName'] . ' ' . $patient['lastName'] ?></span><br>
                <p><strong>Patient ID:</strong> <?php echo $patient_id ?></p>
                <p><strong>Gender:</strong> <?php echo $patient['Gender'] ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $patient['DoB'] ?></p>
                <p><strong>Email:</strong> <?php echo $patient['emailAddress'] ?></p>
            </div>
            
            <nav>
                <a id="linkToBook" href="../booking/booking.php">Book an appointment.</a>
            </nav>
        </div>
    </section>

    <table id="patientAppointments">
        <thead>
            <tr>
                <th>Time</th>
                <th>Date</th>
                <th>Doctor's Name</th>
                <th>Doctor's Photo</th>
                <th>Status</th>
                <th>Cancel</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_appointments)): ?>
                <tr>
                    <td><?php echo $row['time'] ?></td>
                    <td><?php echo $row['date'] ?></td>
                    <td><?php echo $row['doctor_name'] ?></td>
                    <td>
                        <img src="../DBimages/<?php echo $row['doctor_photo'] ?>" 
                             alt="Doctor Photo" class="patientDoctorPhoto">
                    </td>
                    <td><?php echo $row['status'] ?></td>
                    <td>
                        <button class="patientCancel" onclick="cancelAppointment(<?= $row['ID'] ?>, this)">Cancel</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

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
    <script>
function cancelAppointment(appointmentId, buttonElement) {
    if (!confirm("Are you sure you want to cancel this appointment?")) return;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "cancel_appointment_ajax.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

   xhr.onload = function () {
    console.log("Server response:", xhr.responseText); // 👈 أضف هذا
    if (xhr.status === 200 && xhr.responseText.trim() === "true") {
        const row = buttonElement.closest("tr");
        row.remove(); 
    } else {
        alert("Failed to cancel appointment. Please try again.");
    }
};


    xhr.send("id=" + appointmentId);
}
</script>

</body>
</html>

<?php
// Close MySQL connection
mysqli_close($conn);
?>