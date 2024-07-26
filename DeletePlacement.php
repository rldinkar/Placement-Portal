<?php
include("database.php");

if (mysqli_connect_errno()) {
    exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $placementId = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM placements WHERE placement_id = ?");
    $stmt->bind_param("i", $placementId);

    if ($stmt->execute()) {
        echo "Placement record deleted successfully.";
    } else {
        echo "Error deleting placement record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
