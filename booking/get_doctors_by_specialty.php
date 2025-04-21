<?php
$con = mysqli_connect('localhost', 'root', 'root', 'cura');
if (!$con) {
    echo json_encode([]);
    exit;
}

if (isset($_POST['specialty'])) {
    $specialty = $_POST['specialty'];

    if ($specialty === "") {
        $sql = "SELECT ID, firstName, lastName FROM doctor";
        $result = mysqli_query($con, $sql);
    } else {
        $sql = "SELECT d.ID, d.firstName, d.lastName
                FROM doctor d
                INNER JOIN speciality s ON d.SpecialityID = s.ID
                WHERE s.speciality = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $specialty);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }

    $doctors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }

    echo json_encode($doctors);
} else {
    echo json_encode([]);
}

mysqli_close($con);
?>
