<?php
if (isset($_POST["submit"])) {
    $student_id = $_POST["student_id"];
    $first_name = $_POST["first_name"];
    $middle_name = isset($_POST["mname"]) ? $_POST["mname"] : NULL;
    $last_name = $_POST["lname"];
    $email = $_POST["email"];
    $mobile_no = isset($_POST["moname"]) ? $_POST["moname"] : NULL;
    $gender = $_POST["gender"];
    $course = $_POST["course"];
    $year = isset($_POST["year"]) ? $_POST["year"] : NULL;
    $cgpa = isset($_POST["cgpa"]) ? $_POST["cgpa"] : NULL;

    $errors = array();

    if (empty($student_id) || empty($first_name) || empty($last_name) || empty($email) || empty($gender) || empty($course) || empty($cgpa)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
   

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
       
        require_once "database.php";
      
        $sql = "INSERT INTO students (student_id, first_name, middle_name, last_name, email, mobile_no, gender, course, year, cgpa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "issssssssd", $student_id, $first_name, $middle_name, $last_name, $email, $mobile_no, $gender, $course, $year, $cgpa);
            mysqli_stmt_execute($stmt);
            echo "<div class='container text-center mt-5'>
                    <div class='alert alert-success'>Student added successfully.</div>
                    <button onclick=\"window.location.href = 'add_student.html';\" class='btn btn-primary'>Back to Add Student Form</button>
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

