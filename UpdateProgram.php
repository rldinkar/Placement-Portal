<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST['program_id']) &&
    isset($_POST['name']) &&
    isset($_POST['description']) &&
    isset($_POST['start_date'])) {
    $programId = mysqli_real_escape_string($conn, $_POST['program_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $startDate = mysqli_real_escape_string($conn, $_POST['start_date']);
    $endDate = mysqli_real_escape_string($conn, $_POST['end_date']);

    if (empty($programId) || empty($name) || empty($description) || empty($startDate)) {
        http_response_code(400);
        echo "All fields are required.";
        exit();
    }
    $query = "UPDATE training_programs SET name = '$name', description = '$description', start_date = '$startDate', end_date = '$endDate' WHERE program_id = '$programId'";
    if (mysqli_query($conn, $query)) {
        echo "Program updated successfully.";
    } else {
        http_response_code(500);
        echo "Error updating program: " . mysqli_error($conn);
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
