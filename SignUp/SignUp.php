<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$connect = mysqli_connect("localhost", "root", "root", "cura",8889);
$error = mysqli_connect_error();
$successMsg = "";
$errorMsg = "";
$selectedRole = $_POST["role"] ?? null;

if ($error) {
    exit("Database connection failed: $error");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["role"])) {
    if ($_POST["role"] === "patient") {
        $id = $_POST["id"];
        $firstName = $_POST["first_name"];
        $lastName = $_POST["last_name"];
        $gender = $_POST["gender"];
        $dob = $_POST["dob"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $sql = "INSERT INTO patient (ID, firstName, lastName, Gender, DoB, emailAddress, password)
                VALUES ('$id', '$firstName', '$lastName', '$gender', '$dob', '$email', '$password')";

        if (mysqli_query($connect, $sql)) {
            $_SESSION["patient_ID"] = $id;
            $_SESSION["USER_TYPE"] = "patient";
            header("Location: ../Patient/patient.php");
            exit();
        } else {
            $errorMsg = "Patient signup failed: " . mysqli_error($connect);
        }
    }

    if ($_POST["role"] === "doctor") {
        $id = $_POST["id"];
        $firstName = $_POST["first_name"];
        $lastName = $_POST["last_name"];
        $specialityID = $_POST["speciality"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // ✅ Handle doctor photo upload
        $uploadDir = "../DBimages/";
        $ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $uniqueFileName = uniqid("doctor_") . "." . $ext;
        $targetPath = $uploadDir . $uniqueFileName;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath)) {
            $sql = "INSERT INTO doctor (ID, firstName, lastName, specialityID, emailAddress, password, uniqueFileName)
                    VALUES ('$id', '$firstName', '$lastName', '$specialityID', '$email', '$password', '$uniqueFileName')";

            if (mysqli_query($connect, $sql)) {
                $_SESSION["DOCTOR_ID"] = $id;
                $_SESSION["USER_TYPE"] = "doctor";
                header("Location: ../Doctor/doctor.php");
                exit();
            } else {
                $errorMsg = "Doctor signup failed: " . mysqli_error($connect);
            }
        } else {
            $errorMsg = "Failed to upload photo. Please try again.";
        }
    }
}
$specialities = [];
$speciality_query = "SELECT ID, speciality FROM speciality";
$speciality_result = mysqli_query($connect, $speciality_query);

if ($speciality_result && mysqli_num_rows($speciality_result) > 0) {
    while ($row = mysqli_fetch_assoc($speciality_result)) {
        $specialities[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CURA - Sign Up</title>
    <link rel="stylesheet" href="../Main.css">
    <link rel="stylesheet" href="SignUp.css">
    <style>
        .hidden { display: none; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="../Home/Home.php" class="logo"><span>CURA</span></a>
    </div>
</nav>

<section class="signup-section">
    <div class="left-column">
        <p>Welcome to</p> 
        <h2>CURA Clinic.</h2>
    </div>

    <div class="form-container">
        <h1>Create an Account</h1>

        <?php if (!empty($successMsg)) echo "<p class='success'>$successMsg</p>"; ?>
        <?php if (!empty($errorMsg)) echo "<p class='error'>$errorMsg</p>"; ?>

        <div id="role-selection">
            <h3>Select Your Role</h3>
            <div class="radio-group">
                <label>
                    <input type="radio" name="role" value="patient" <?= $selectedRole === "patient" ? "checked" : "" ?> onchange="showForm()"> Patient
                </label>
                <label>
                    <input type="radio" name="role" value="doctor" <?= $selectedRole === "doctor" ? "checked" : "" ?> onchange="showForm()"> Doctor
                </label>
            </div>
        </div>

        <!-- ✅ Patient Form -->
        <form id="patient-form" class="<?= $selectedRole === 'patient' ? '' : 'hidden' ?>" action="SignUp.php" method="POST">
            <input type="hidden" name="role" value="patient">
            <div class="form-row">
                <div class="form-group">
                    <label for="patient-first-name">First Name</label>
                    <input type="text" id="patient-first-name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="patient-last-name">Last Name</label>
                    <input type="text" id="patient-last-name" name="last_name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="patient-id">Patient ID</label>
                    <input type="text" id="patient-id" name="id" required>
                </div>
                <div class="form-group">
                    <label for="patient-gender">Gender</label>
                    <select id="patient-gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="patient-dob">Date of Birth</label>
                    <input type="date" id="patient-dob" name="dob" required>
                </div>
                <div class="form-group">
                    <label for="patient-email">Email</label>
                    <input type="email" id="patient-email" name="email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="patient-password">Password</label>
                    <input type="password" id="patient-password" name="password" required>
                </div>
            </div>
            <button type="submit" class="Signupbtn">Sign Up</button>
        </form>

        <!-- ✅ Doctor Form -->
        <form id="doctor-form" class="<?= $selectedRole === 'doctor' ? '' : 'hidden' ?>" action="SignUp.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="role" value="doctor">
            <label for="doctor-first-name">First Name</label>
            <input type="text" id="doctor-first-name" name="first_name" required>

            <label for="doctor-last-name">Last Name</label>
            <input type="text" id="doctor-last-name" name="last_name" required>

            <label for="doctor-id">ID</label>
            <input type="text" id="doctor-id" name="id" required>

<label for="doctor-speciality">Speciality</label>
<select id="doctor-speciality" name="speciality" required>
    <option value="">Select Speciality</option>
    <?php foreach ($specialities as $spec): ?>
        <option value="<?= $spec['ID'] ?>">
            <?= htmlspecialchars($spec['speciality']) ?>
        </option>
    <?php endforeach; ?>
</select>


            <label for="doctor-email">Email</label>
            <input type="email" id="doctor-email" name="email" required>

            <label for="doctor-password">Password</label>
            <input type="password" id="doctor-password" name="password" required>

            <!-- ✅ Photo Upload -->
            <label for="doctor-photo">Profile Photo</label>
            <input type="file" id="doctor-photo" name="photo" accept="image/*" required>

            <button type="submit" class="Signupbtn">Sign Up</button>
        </form>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>&copy; 2025 CURA | All Rights Reserved</p>
        <p class="contact-info">
            <strong>Address:</strong> 7720 Riyadh, Laysen Valley, 44321<br>
            <strong>Phone:</strong> (966) 556-789-5900
        </p>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    function showForm() {
        const selectedRole = document.querySelector('input[name="role"]:checked');
        const patientForm = document.getElementById('patient-form');
        const doctorForm = document.getElementById('doctor-form');

        if (!selectedRole) return;

        if (selectedRole.value === 'patient') {
            patientForm.classList.remove('hidden');
            doctorForm.classList.add('hidden');
        } else {
            doctorForm.classList.remove('hidden');
            patientForm.classList.add('hidden');
        }
    }

    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.addEventListener('change', showForm);
    });

    showForm(); // On page load
});
</script>

</body>
</html>
