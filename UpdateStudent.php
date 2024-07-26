<?php
include("database.php");

if (mysqli_connect_errno()) {
    exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentId = $_POST['student_id'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $mobileNo = $_POST['mobile_no'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $cgpa = $_POST['cgpa'];

    $stmt = $conn->prepare("UPDATE students SET first_name=?, middle_name=?, last_name=?, email=?, mobile_no=?, gender=?, course=?, year=?, cgpa=? WHERE student_id=?");
    $stmt->bind_param("sssssssssi", $firstName, $middleName, $lastName, $email, $mobileNo, $gender, $course, $year, $cgpa, $studentId);

    if ($stmt->execute()) {
        echo "Student details updated successfully.";
    } else {
        echo "Error updating student details: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
