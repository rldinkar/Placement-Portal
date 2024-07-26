<?php
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    $errors = array();

    if (empty($name) || empty($description) || empty($start_date) || empty($end_date)) {
        array_push($errors, "All fields are required");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        require_once "database.php";
      
        $sql = "INSERT INTO training_programs (name, description, start_date, end_date) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $description, $start_date, $end_date);
            mysqli_stmt_execute($stmt);
            echo "<div class='container text-center mt-5'>
                    <div class='alert alert-success'>Training Program added successfully.</div>
                    <button onclick=\"window.location.href = 'add_training_program.html';\" class='btn btn-primary'>Back to Form</button>
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