<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['programId'])) {
    $programId = mysqli_real_escape_string($conn, $_GET['programId']);

    if (empty($programId)) {
        http_response_code(400);
        echo "Program ID is required.";
        exit();
    }
    $query = "SELECT * FROM training_programs WHERE program_id = '$programId'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo "Program not found.";
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
