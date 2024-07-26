<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: student_login.html");
    exit();
}
include("database.php");
$student_id = '';
$student_data = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fetch_profile'])) {
        $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';
        $student_id = filter_var($student_id, FILTER_SANITIZE_NUMBER_INT);

        if (!empty($student_id)) {
            $query = "SELECT * FROM students WHERE student_id = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param('i', $student_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $student_data = $result->fetch_assoc();
                } else {
                    $message = "No student found with ID: " . htmlspecialchars($student_id);
                }
            } else {
                $message = "Database error: " . $conn->error;
            }
        } else {
            $message = "Please enter a valid student ID.";
        }
    } elseif (isset($_POST['update_profile'])) {
        $student_id = filter_var($_POST['student_id'], FILTER_SANITIZE_NUMBER_INT);
        $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
        $middle_name = filter_var($_POST['middle_name'], FILTER_SANITIZE_STRING);
        $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $mobile_no = filter_var($_POST['mobile_no'], FILTER_SANITIZE_STRING);
        $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
        $course = filter_var($_POST['course'], FILTER_SANITIZE_STRING);
        $year = filter_var($_POST['year'], FILTER_SANITIZE_NUMBER_INT);
        $cgpa = filter_var($_POST['cgpa'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $query = "UPDATE students SET first_name = ?, middle_name = ?, last_name = ?, email = ?, mobile_no = ?, gender = ?, course = ?, year = ?, cgpa = ? WHERE student_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('sssssssidi', $first_name, $middle_name, $last_name, $email, $mobile_no, $gender, $course, $year, $cgpa, $student_id);
            $stmt->execute();

            if ($stmt->affected_rows == 1) {
                $message = "Profile updated successfully.";
            } else {
                $message = "No changes were made to the profile.";
            }
        } else {
            $message = "Database error: " . $conn->error;
        }
        $query = "SELECT * FROM students WHERE student_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $student_data = $result->fetch_assoc();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="nav_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <style>
        .navbar-light .navbar-nav .nav-link {
            color: #000;
        }
    </style>
</head>

<body>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <a class="navbar-brand" href="index.html">Placement Portal</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student_profile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_jobs.php">Job Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Student Profile</h1>

        <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF"]); ?>" method="post" class="mb-4 form-container">
            <div class="form-group">
                <label for="student_id">Enter Student ID:</label>
                <input type="text" id="student_id" name="student_id" class="form-control"
                    value="<?php echo htmlspecialchars($student_id); ?>" required>
            </div>
            <div class="center-btn">
                <button type="submit" name="fetch_profile" class="btn btn-primary center-btn">Fetch Profile</button>
            </div>
        </form>

        <?php if (!empty($student_data)): ?>
        <h2 class="mb-4 text-center">Edit Student Information</h2>
        <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF"]); ?>" method="post" class="form-container">
            <input type="hidden" name="student_id" value="<?php echo $student_data['student_id']; ?>">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" class="form-control"
                    value="<?php echo $student_data['first_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="middle_name">Middle Name:</label>
                <input type="text" id="middle_name" name="middle_name" class="form-control"
                    value="<?php echo $student_data['middle_name']; ?>">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" class="form-control"
                    value="<?php echo $student_data['last_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="<?php echo $student_data['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="mobile_no">Mobile No:</label>
                <input type="text" id="mobile_no" name="mobile_no" class="form-control"
                    value="<?php echo $student_data['mobile_no']; ?>">
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <input type="text" id="gender" name="gender" class="form-control"
                    value="<?php echo $student_data['gender']; ?>" required>
            </div>
            <div class="form-group">
                <label for="course">Course:</label>
                <input type="text" id="course" name="course" class="form-control"
                    value="<?php echo $student_data['course']; ?>" required>
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="text" id="year" name="year" class="form-control"
                    value="<?php echo $student_data['year']; ?>" required>
            </div>
            <div class="form-group">
                <label for="cgpa">CGPA:</label>
                <input type="text" id="cgpa" name="cgpa" class="form-control"
                    value="<?php echo $student_data['cgpa']; ?>">
            </div>
            <div class="form-group center-btn">
                <button type="submit" name="update_profile" class="btn btn-success">Update Profile</button>
                <a href="student_dashboard.php" class="btn btn-secondary btn-back ">Back to Dashboard</a>
            </div>
        </form>
        <?php endif; ?>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>