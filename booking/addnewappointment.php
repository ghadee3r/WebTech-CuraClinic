<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$con = mysqli_connect('localhost', 'root', 'root', 'cura', '8889');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}





$doctorID = $_POST['doctor_id'];
$date = $_POST['date'];
$time = $_POST['time'];
$reason = $_POST['reason'];


$patientID = $_SESSION['patient_ID'] ?? 1;


$status = 'Pending';

$sql = "INSERT INTO appointment (patientID, doctorID, date, time, reason, status)
        VALUES ('$patientID', '$doctorID', '$date', '$time', '$reason', '$status')";

if (mysqli_query($con, $sql)) {
    // Success - Redirect to homepage with message
    header("Location: patient_homepage.php?message=Appointment+requested+successfully");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($con);
}

mysqli_close($con);
?>


 <?php /*
if (isset($_GET['message'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['message']) . "</p>";
}*/
?>

