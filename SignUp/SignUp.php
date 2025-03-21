<?php
session_start();

// Connect to database
$connect = mysqli_connect("localhost", "root", "root", "cura");
$error = mysqli_connect_error();
$successMsg = "";
$errorMsg = "";

if ($error) {
    exit("Database connection failed: $error");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["role"])) {
    if ($_POST["role"] === "patient") {
        $id = $_POST["id"];
        $firstName = $_POST["first_name"];
        $lastName = $_POST["last_name"];
        $gender = $_POST["gender"];
        $dob = $_POST["dob"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "INSERT INTO patients (PatientID, FirstName, LastName, Gender, DOB, Email, Password)
                VALUES ('$id', '$firstName', '$lastName', '$gender', '$dob', '$email', '$password')";

        if (mysqli_query($connect, $sql)) {
            $successMsg = "Patient signed up successfully!";
            $selectedRole = "patient";
        } else {
            $errorMsg = "Error: " . mysqli_error($connect);
        }
    }

    if ($_POST["role"] === "doctor") {
        $id = $_POST["id"];
        $firstName = $_POST["first_name"];
        $lastName = $_POST["last_name"];
        $speciality = $_POST["speciality"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $photo = ""; // Optional: handle file upload

        $sql = "INSERT INTO doctors (DoctorID, FirstName, LastName, Speciality, Email, Password)
                VALUES ('$id', '$firstName', '$lastName', '$speciality', '$email', '$password')";

        if (mysqli_query($connect, $sql)) {
            $successMsg = "Doctor signed up successfully!";
            $selectedRole = "doctor";
        } else {
            $errorMsg = "Error: " . mysqli_error($connect);
        }
    }
}
?>

﻿<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - Sign Up</title>
    <link rel="stylesheet" href="../Main.css">
    <link rel="stylesheet" href="SignUp.css">
</head>

<body>
    
    <nav class="navbar">
        <div class="container">
            <a href="../Home/Home.php" class="logo">
                <span>CURA</span>
            </a>
        </div>
    </nav>
    
    <section class="signup-section">
        <div class="left-column">



                 <p>Welcome to</p> 
                 <h2>CURA Clinic.</h2>
        </div>
        <div class="form-container">
            <h1>Create an Account</h1>
            <div id="role-selection">
                <h3>Select Your Role</h3>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="role" value="patient" onchange="showForm()"> Patient
                    </label>
                    <label>
                        <input type="radio" name="role" value="doctor" onchange="showForm()"> Doctor
                    </label>
                </div>
            </div>
            <form id="patient-form" class="hidden" action="" method="POST">
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

            <form id="doctor-form" class="hidden" action="" method="POST">
                <label for="doctor-first-name">First Name</label>
                <input type="text" id="doctor-first-name" name="first_name" required>

                <label for="doctor-last-name">Last Name</label>
                <input type="text" id="doctor-last-name" name="last_name" required>

                <label for="doctor-id">ID</label>
                <input type="text" id="doctor-id" name="id" required>

                <label for="doctor-photo">Photo</label>
                <input type="file" id="doctor-photo" name="photo" accept="image/*" required>

                <label for="doctor-speciality">Speciality</label>
                <select id="doctor-speciality" name="speciality" required>
                    <option value="">Select Speciality</option>
                    <option value="Child">Parent & Child Therapy</option>
                    <option value="couple">Individual Conseling</option>
                    <option value="Psychiatry">Workshops & Seminars</option>

                </select>

                <label for="doctor-email">Email</label>
                <input type="email" id="doctor-email" name="email" required>

                <label for="doctor-password">Password</label>
                <input type="password" id="doctor-password" name="password" required>

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
            <ul class="social-links">
                <li><img src="../images/twitter.webp" alt="Twitter"></li>
                <li><img src="../images/free-instagram-logo-icon-3497-thumb.png" alt="Instagram"></li>
                <li><img src="../images/Email542689.png" alt="Email"></li>
            </ul>
        </div>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
    function showForm() {
        const selectedRole = document.querySelector('input[name="role"]:checked').value;
        const patientForm = document.getElementById('patient-form');
        const doctorForm = document.getElementById('doctor-form');

        if (selectedRole === 'patient') {
            patientForm.classList.remove('hidden');
            doctorForm.classList.add('hidden');
        } else if (selectedRole === 'doctor') {
            doctorForm.classList.remove('hidden');
            patientForm.classList.add('hidden');
        }
    }

   
    const radioButtons = document.querySelectorAll('input[name="role"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', showForm);
    });
    });
    </script>
</body>

</html>
