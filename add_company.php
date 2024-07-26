<?php
if (isset($_POST["submit"])) {
    $company_name = $_POST["company_name"];
    $location = $_POST["location"];
    $website = $_POST["website"];
    $contact_email = $_POST["contact_email"];
    $package = $_POST["package"];

    $errors = array();

    if (empty($company_name) || empty($location) || empty($website) || empty($contact_email) || empty($package)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Contact Email is not valid");
    }
   

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
       
        require_once "database.php";
      
        $sql = "INSERT INTO company (company_name, location, website, contact_email, package) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssd", $company_name, $location, $website, $contact_email, $package);
            mysqli_stmt_execute($stmt);
            echo "<div class='container text-center mt-5'>
                    <div class='alert alert-success'>Company added successfully.</div>
                    <button onclick=\"window.location.href = 'add_company.html';\" class='btn btn-primary'>Back to Add Company Form</button>
                  </div>";
            
        } else {
            echo "<div class='alert alert-danger'>Error: Unable to prepare SQL statement.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Company</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("image1.png");
            background-position: center;
            background-size: cover; 
            background-repeat: no-repeat;
            background-attachment: fixed; 
            color: white; 
        }
    </style>
</head>
<body>
   
</body>
</html>