<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $programId = mysqli_real_escape_string($conn, $_POST['id']);

    if (empty($programId)) {
        http_response_code(400);
        echo "Program ID is required.";
        exit();
    }
    $query = "DELETE FROM training_programs WHERE program_id = '$programId'";
    if (mysqli_query($conn, $query)) {
        echo "Program deleted successfully.";
    } else {
        http_response_code(500);
        echo "Error deleting program: " . mysqli_error($conn);
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
