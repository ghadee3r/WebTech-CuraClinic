
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURA - Make an appointment</title>
    <!-- External CSS -->
    <link rel="stylesheet" href="booking.css">
    <link rel="stylesheet" href="Main.css">
    <script src=""></script>

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

  
  <!-- Appointment Booking Forms -->
  <main class="booking-container">
    <h2>Book an Appointment</h2>
    <p>Select your desired specialty and fill in the details below.</p>

    <!-- First Form: Specialty Selection -->
    <form class="specialty-form" action='booking.php'>
      <div class="form-group">
        <label for="specialty">Choose a Specialty:</label>
        <select id="specialty" name="specialty" required>
          
          <option value="" disabled selected>Select a specialty</option>

          <?php
         
          ini_set('display_errors', '1');
          error_reporting(E_ALL);
          
          $con= mysqli_connect('localhost','root','root','cura','8889');
          $sqlsp="SELECT * FROM speciality";
          $resp= mysqli_query($con, $sqlsp);
          while ($row = mysqli_fetch_assoc($resp)){
              echo "<option value='{$row['specialty']}'>{$row['specialty']}</option>";
          };
          
          

          ?>
          <!-- 
          <option value="Child">Parent & Child Therapy</option>
          <option value="couple">Individual Conseling</option>
          <option value="Workshops">Workshops & Seminars</option>
-->
        </select>
      </div>
      <button type="submit" class="submit-button">Next</button>
    </form>

    <form class="appointment-form">
      <div class="form-group">
        <label for="doctor">Choose a Doctor:</label>
        <select id="doctor" name="doctor" required>
          <option value="" disabled selected>Select a doctor</option>
          <!-- Options will be dynamically populated based on specialty -->
          <option ></option>
          <option ></option>
        </select>
      </div>

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
        <textarea id="reason" name="reason" rows="4" placeholder="Briefly describe the reason for your visit..." required></textarea>
      </div>

      <button type="submit" class="submit-button">Book Appointment</button>
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
              <li><img src="images/twitter.webp" alt="Twitter"></li>
              <li><img src="images/free-instagram-logo-icon-3497-thumb.png" alt="Instagram"></li>
              <li><img src="images/Email542689.png" alt="Email"></li>
          </ul>
      </div>
  </footer>
  <!-- Footer End -->
</body>

</html>
