<?php
include("database.php");

$companyId = $_GET['companyId'];

$query = "SELECT * FROM company WHERE company_id = $companyId";

$result = mysqli_query($conn, $query);

if ($result) {
    $companyData = mysqli_fetch_assoc($result);
    echo json_encode($companyData);
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_close($conn);
?>