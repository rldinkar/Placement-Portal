<?php
include("database.php");

if(isset($_GET['studentId'])) {
    $studentId = $_GET['studentId'];
    $query = "SELECT * FROM students WHERE student_id = $studentId";
    $result = mysqli_query($conn, $query);
    if($result && mysqli_num_rows($result) > 0) {
        $studentData = mysqli_fetch_assoc($result);
        echo json_encode($studentData);
    } else {
        echo "Student details not found";
    }
} else {
    echo "Student ID not provided";
}
?>
