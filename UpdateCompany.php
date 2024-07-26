<?php
include("database.php");
if(isset($_POST['company_id'], $_POST['company_name'], $_POST['location'], $_POST['website'], $_POST['contact_email'], $_POST['package'])) {

    $company_id = $_POST['company_id'];
    $company_name = $_POST['company_name'];
    $location = $_POST['location'];
    $website = $_POST['website'];
    $contact_email = $_POST['contact_email'];
    $package = $_POST['package'];
    $query = "UPDATE company SET company_name='$company_name', location='$location', website='$website', contact_email='$contact_email', package='$package' WHERE company_id=$company_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
      echo "Company details updated successfully!";
    } else {
      echo "Error updating company details: " . mysqli_error($conn);
    }
} else {
    echo "Error: Missing or invalid POST data.";
}
mysqli_close($conn);
?>
