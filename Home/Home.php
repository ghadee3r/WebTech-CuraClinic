<?php
session_start();

if (isset($_SESSION['DOCTOR_ID'])) {
    $redirectLogin = '../Doctor/doctor.php';
    $redirectSignup = '../Doctor/doctor.php';
} elseif (isset($_SESSION['patient_ID'])) { 
    $redirectLogin = '../Patient/patient.php';
    $redirectSignup = '../Patient/patient.php';
} else {
    $redirectLogin = '../Login/logIn.php';
    $redirectSignup = '../SignUp/SingUp.php';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - Home</title>
    <!-- External CSS -->
    <link rel="stylesheet" href="Home.css">
    <link rel="stylesheet" href="../Main.css">
</head>

<body>

<!-- Navbar Start -->
<nav class="navbar" style="box-shadow: 0 5px 6px rgba(0, 0, 0, 0.1);">
    <div class="container">
        <a href="Home.php" class="logo">
            <span>CURA</span>
        </a>
    </div>
</nav>
<!-- Navbar End -->

<div class="hero">
    <div class="hero-content">
        <h1>Welcome to CURA</h1>
        <p>Your trusted clinic for comprehensive health care solutions.</p>
        <div class="hero-buttons">
            <!-- These buttons always appear, but their links change depending on login state -->
            <a href="<?php echo $redirectLogin; ?>" class="btn">Log In</a>
            <a href="<?php echo $redirectSignup; ?>" class="btn">Sign Up</a>
        </div>
    </div>
</div>

<section class="about">
    <div class="container about-container">
        <div class="about-left">
            <img src="../images/homeAbout.jpg" alt="Clinic Team">
        </div>
        <div class="about-right">
            <h2>Why You Should Trust Us?</h2>
            <p>
                At CURA Clinic, we prioritize your health and well-being. Our experienced professionals are committed to delivering personalized care. We utilize the latest technologies to provide you with the best healthcare experience possible.
            </p>
            <ul class="about-list">
                <li><span class="check-icon">✔</span> Quality health care</li>
                <li><span class="check-icon">✔</span> Only Qualified Doctors</li>
                <li><span class="check-icon">✔</span> Medical Research Professionals</li>
            </ul>
        </div>
    </div>
</section>

<hr class="dark-green-line">

<section class="services">
    <div class="container">
        <h1>Our Services</h1>
        <div class="service-cards">
            <div class="service-card">
                <div class="circle">
                    <img src="../images/Looking For A New Therapist_ 3 Expert Tips For Finding The Right Fit.jpeg" alt="Counseling Service">
                </div>
                <h3>Individual Counseling</h3>
                <p>Personalized sessions to address mental health challenges with expert therapists.</p>
            </div>
            <div class="service-card">
                <div class="circle">
                    <img src="../images/Compassion & Empathy_ Key Qualities for Strong Leaders & Communities.jpeg" alt="Couples Therapy">
                </div>
                <h3>Couples Therapy</h3>
                <p>Helping couples navigate their relationship challenges and strengthen bonds.</p>
            </div>
            <div class="service-card">
                <div class="circle">
                    <img src="../images/121 Best And Inspirational Parenting Quotes Of All Time.jpeg" alt="Parent & Child Therapy">
                </div>
                <h3>Parent & Child Therapy</h3>
                <p>Supporting families by fostering stronger bonds and resolving conflicts together.</p>
            </div>
            <div class="service-card">
                <div class="circle">
                    <img src="../images/Ashley Buzzy.jpeg" alt="Workshops">
                </div>
                <h3>Workshops & Seminars</h3>
                <p>Interactive sessions on stress management, mindfulness, and personal growth.</p>
            </div>
        </div>
    </div>
</section>

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
<!-- Footer End -->

</body>
</html>
