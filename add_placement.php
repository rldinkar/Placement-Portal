<?php
if (isset($_POST["submit"])) {
    $company_name = $_POST["company_name"];
    $student_id = $_POST["student_id"];
    $role = $_POST["role"];
    $placement_date = $_POST["placement_date"];
    $placement_type = $_POST["placement_type"];

    $errors = array();

    if (empty($company_name) || empty($student_id) || empty($role) || empty($placement_date) || empty($placement_type)) {
        array_push($errors, "All fields are required");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        require_once "database.php";
      
        
        $query = "SELECT company_id FROM company WHERE company_name = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $company_name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $company_id);
                mysqli_stmt_fetch($stmt);
            } else {
                echo "<div class='alert alert-danger'>Error: Company not found.</div>";
                exit(); 
            }
        } else {
            echo "<div class='alert alert-danger'>Error: Unable to prepare SQL statement.</div>";
            exit(); 
        }
      
        $sql = "INSERT INTO placements (company_id, student_id, role, placement_date, placement_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "iisss", $company_id, $student_id, $role, $placement_date, $placement_type);
            mysqli_stmt_execute($stmt);
            echo "<div class='container text-center mt-5'>
                    <div class='alert alert-success'>Placement added successfully.</div>
                    <button onclick=\"window.location.href = 'add_placement.html';\" class='btn btn-primary'>Back to Add Placement Form</button>
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