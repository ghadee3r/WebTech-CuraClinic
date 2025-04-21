<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../security.php';
curaSecurity('patient');

// Database connection
$con = mysqli_connect('localhost', 'root', 'root', 'cura');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$specialties = [];
$doctors = [];

// Get all specialties
$sql_specialties = "SELECT * FROM speciality";
$result_specialties = mysqli_query($con, $sql_specialties);
while ($row = mysqli_fetch_assoc($result_specialties)) {
    $specialties[] = $row;
}

// Show all doctors by default
$sql_doctors = "SELECT d.*, s.speciality AS speciality_name
                FROM doctor d
                INNER JOIN speciality s ON d.SpecialityID = s.ID";
$result_doctors = mysqli_query($con, $sql_doctors);
while ($row = mysqli_fetch_assoc($result_doctors)) {
    $doctors[] = $row;
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

    <!-- Specialty Dropdown (No form submission) -->
<form>
    <div class="form-group">
        <label for="specialty">Choose a Specialty:</label>
        <select id="specialty" name="speciality" class="styled-select" required>
            <option value="" disabled selected>Select a specialty</option>
            <?php foreach ($specialties as $specialty): ?>
                <option value="<?php echo $specialty['speciality']; ?>">
                    <?php echo $specialty['speciality']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

    <!-- FORM 2: Choose a Doctor and Book Appointment -->
    <form class="appointment-form" action="addnewappointment.php" method="POST">
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

        <input type="hidden" name="hiddenDoctorID" id="hiddenDoctorID" value="">

        <div class="form-group">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" required>
        </div>

        <div class="form-group">
            <label for="time">Select Time:</label>
            <input type="time" id="time" name="time" required>
        </div>

        <div class="form-group">
            <label for="reason">Reason for Visit:</label>
            <textarea id="reason" name="reason" rows="4" required></textarea>
        </div>

        <input type="submit" class="submit-button" value="Book Appointment">
    </form>
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#specialty').on('change', function () {
        var selectedSpecialty = $(this).val();

        $.ajax({
            url: 'get_doctors_by_specialty.php',
            type: 'POST',
            data: { specialty: selectedSpecialty },
            success: function (response) {
                const doctors = JSON.parse(response);
                const doctorSelect = $('#doctor');
                doctorSelect.empty().append('<option value="" disabled selected>Select a doctor</option>');

                if (doctors.length > 0) {
                    doctors.forEach(doc => {
                        doctorSelect.append(`<option value="${doc.ID}">${doc.firstName} ${doc.lastName}</option>`);
                    });
                } else {
                    doctorSelect.append('<option disabled>No doctors found</option>');
                }
            }
        });
    });

    $('#doctor').on('change', function () {
        $('#hiddenDoctorID').val($(this).val());
    });
});
</script>

</body>
</html>

<?php
mysqli_close($con);
?>
