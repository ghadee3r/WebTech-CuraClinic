<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$con = mysqli_connect('localhost', 'root', 'root', 'cura', '3306');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$specialties = [];
$doctors = [];

// Retrieve all specialties for the first form
$sql_specialties = "SELECT * FROM speciality";
$result_specialties = mysqli_query($con, $sql_specialties);

while ($row = mysqli_fetch_assoc($result_specialties)) {
    $specialties[] = $row;
}

// If the request is GET: Retrieve all doctors and their specialities (optional, to display all doctors initially)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql_doctors = "SELECT d.*, s.speciality AS speciality_name
                    FROM doctor d
                    INNER JOIN speciality s ON d.SpecialityID = s.ID";
    $result_doctors = mysqli_query($con, $sql_doctors);

    while ($row = mysqli_fetch_assoc($result_doctors)) {
        $doctors[] = $row;
    }
}

// If the first form is submitted (POST): Filter doctors by the selected specialty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['speciality'])) {
    $selectedSpecialty = $_POST['speciality'];

    $sql_doctors = "SELECT d.*, s.speciality AS speciality_name
                    FROM doctor d
                    INNER JOIN speciality s ON d.SpecialityID = s.ID
                    WHERE s.speciality = '$selectedSpecialty'";
    $result_doctors = mysqli_query($con, $sql_doctors);

    $doctors = [];
    while ($row = mysqli_fetch_assoc($result_doctors)) {
        $doctors[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - Book an Appointment</title>
    <link rel="stylesheet" href="booking.css">
    <link rel="stylesheet" href="../Main.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="container">
        <a href="../Home/Home.php" class="logo"><span>CURA</span></a>
    </div>
</nav>

<!-- Booking Container -->
<main class="booking-container">
    <h2>Book an Appointment</h2>
    <p>Select your desired specialty and fill in the details below.</p>

    <!-- FORM 1: Choose a Specialty -->
    <form class="specialty-form" action="booking.php" method="POST">
        <div class="form-group">
            <label for="specialty">Choose a Specialty:</label>
            <select id="specialty" name="speciality" required>
                <option value="" disabled selected>Select a specialty</option>
                <?php foreach ($specialties as $specialty): ?>
                    <option value="<?php echo $specialty['speciality']; ?>">
                        <?php echo $specialty['speciality']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="submit-button">Next</button>
    </form>

    <!-- FORM 2: Choose a Doctor and Book Appointment -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($doctors)): ?>
        <form class="appointment-form" action="addnewappointment.php" method="POST">
            
            <!-- Doctor selection -->
            <div class="form-group">
                <label for="doctor">Choose a Doctor:</label>
                <select id="doctor" name="doctorID" required>
                    <option value="" disabled selected>Select a doctor</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?php echo $doctor['ID']; ?>">
                            <?php echo $doctor['firstName'] . ' ' . $doctor['lastName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Hidden doctor ID field -->
            <input type="hidden" name="hiddenDoctorID" id="hiddenDoctorID" value="">

            <!-- Date selection -->
            <div class="form-group">
                <label for="date">Select Date:</label>
                <input type="date" id="date" name="date" required>
            </div>

            <!-- Time selection -->
            <div class="form-group">
                <label for="time">Select Time:</label>
                <input type="time" id="time" name="time" required>
            </div>

            <!-- Reason for visit -->
            <div class="form-group">
                <label for="reason">Reason for Visit:</label>
                <textarea id="reason" name="reason" rows="4" placeholder="Briefly describe the reason for your visit..." required></textarea>
            </div>

            <!-- Submit appointment -->
            <input type="submit" class="submit-button" value="Book Appointment">
        </form>

        <script>
        // Sync hidden doctor ID with doctor selection
        const doctorSelect = document.getElementById('doctor');
        const hiddenDoctorInput = document.getElementById('hiddenDoctorID');

        doctorSelect.addEventListener('change', function() {
            hiddenDoctorInput.value = this.value;
        });
        </script>
    <?php endif; ?>

</main>

<!-- Footer -->
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
