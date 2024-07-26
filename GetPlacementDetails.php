<?php
include("database.php");
if (!isset($_GET['placementId'])) {
    echo json_encode(array('error' => 'Placement ID is missing.'));
    exit;
}

$placementId = $_GET['placementId'];

$query = "
    SELECT placements.*, students.first_name, students.last_name, company.company_name 
    FROM placements 
    JOIN company ON placements.company_id = company.company_id
    JOIN students ON placements.student_id = students.student_id
    WHERE placements.placement_id = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $placementId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo json_encode(array('error' => 'Failed to fetch placement details.'));
    exit;
}

$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo json_encode(array('error' => 'Placement not found.'));
    exit;
}

$response = array(
    'placement_id' => $row['placement_id'],
    'student_id' => $row['student_id'],
    'first_name' => $row['first_name'],
    'last_name' => $row['last_name'],
    'company_name' => $row['company_name'],
    'role' => $row['role'],
    'placement_date' => $row['placement_date'],
    'placement_type' => $row['placement_type']
);

echo json_encode($response);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
