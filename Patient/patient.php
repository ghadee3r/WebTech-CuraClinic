<?php
session_start();
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
$patient_id = $_SESSION['patient_id'];

// Fetch patient details
$query_patient = "SELECT first_name, last_name, email, gender, dob FROM patients WHERE id = $patient_id";
$result_patient = mysqli_query($conn, $query_patient);
$patient = mysqli_fetch_assoc($result_patient);

// Fetch patient appointments
$query_appointments = "
    SELECT a.id, a.date, a.time, a.status, 
           d.first_name AS doctor_name, d.photo AS doctor_photo 
    FROM appointments a 
    JOIN doctors d ON a.doctor_id = d.id 
    WHERE a.patient_id = $patient_id 
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
                <a href="../Home/Home.html" class="logo">
                    <span>CURA</span>
                </a>
            </div>
            <a href="../Home/Home.html" id="patientSignOut" style="margin-left: auto;">Sign Out</a>
        </nav>

            <section id="patientWelcome">
        <div id="patientWelcomeLeft">
            <h2>Welcome, <?php htmlspecialchars($patient['first_name']) ?>!</h2>
            <p>Your journey to wellness starts here.</p> 
        </div>
        
        <div id="patientInfoContainer">
            <div id="patientInfo">
                <br><span id="patientName"><?php htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></span><br>
                <p><strong>Patient ID:</strong> <?php htmlspecialchars($patient_id) ?></p>
                <p><strong>Gender:</strong> <?php htmlspecialchars($patient['gender']) ?></p>
                <p><strong>Date of Birth:</strong> <?php htmlspecialchars($patient['dob']) ?></p>
                <p><strong>Email:</strong> <?php htmlspecialchars($patient['email']) ?></p>
            </div>
                
                <nav>
                    <a id="linkToBook" href="../booking/booking.html">Book an appointment.</a>
                </nav>
            </div>
        </section>

        <table id="patientAppointments">
            <thead>
                <td>Time</td>
                <td>Date</td>
                <td>Doctor's Name</td>
                <td>Doctor's Photo</td>
                <td>Status</td>
                <td></td>
            </thead>
             <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_appointments)): ?>
                <tr>
                    <td><?php htmlspecialchars($row['time']) ?></td>
                    <td><?php htmlspecialchars($row['date']) ?></td>
                    <td><?php htmlspecialchars($row['doctor_name']) ?></td>
                    <td><img src="<?php htmlspecialchars($row['doctor_photo']) ?>" alt="Doctor Photo" class="patientDoctorPhoto"></td>
                    <td><?php htmlspecialchars(ucfirst($row['status'])) ?></td>
                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                            <a href="cancel_appointment.php?id=<?php $row['id'] ?>" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                <button class="patientCancel">Cancel</button>
                            </a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
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
  </html>
  <?php
// Close MySQL connection
mysqli_close($conn);
?>