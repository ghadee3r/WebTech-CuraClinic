<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$database = "cura"; 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database, '8889');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $role = $_POST["role"]; // 'doctor' or 'patient'

    if (!empty($email) && !empty($password) && !empty($role)) {
        if ($role == "doctor") {
            $stmt = $conn->prepare("SELECT ID, password FROM doctor WHERE emailAddress = ?");
        } else {
            $stmt = $conn->prepare("SELECT ID, password FROM patient WHERE emailAddress = ?");
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedPassword);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashedPassword)) {
                if ($role == "doctor") {
                    $_SESSION["DOCTOR_ID"] = $id;
                    header("Location: ../Doctor/doctor.php"); // Redirect to doctor homepage
                    exit();
                } else {
                    $_SESSION["patient_ID"] = $id;
                    header("Location: ../Patient/patient.php"); // Redirect to patient homepage
                    exit();
                }
            } else {
                $errorMessage = "Incorrect password.";
            }
        } else {
            $errorMessage = "User not found.";
        }
    } else {
        $errorMessage = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - Log In</title>
    <link rel="stylesheet" href="logIn.css">
    <link rel="stylesheet" href="../Main.css">
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar">
        <div class="container">
            <a href="../Home/Home.html" class="logo">
                <span>CURA</span>
            </a>
        </div>
    </nav>
    <!-- Navbar End -->

    <main class="login-container">
        <h2>Welcome Back!</h2>
        <p>Please log in to continue.</p>

        <!-- Display Error Messages -->
        <?php if (!empty($errorMessage)) : ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <form action="logIn.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <label>User Role:</label>
                <div class="role-options">
                    <label><input type="radio" name="role" value="patient" required> Patient</label>
                    <label><input type="radio" name="role" value="doctor"> Doctor</label>
                </div>
            </div>

            <input type="submit" class="submit-button" value="Log In">
        </form>
    </main>

    <!-- Footer Start -->
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
