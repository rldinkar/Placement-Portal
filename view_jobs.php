<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: student_login.html");
    exit();
}

include("database.php");

$student_id = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;

    $sql_check_student = "SELECT student_id FROM students WHERE student_id = ?";
    $stmt_check_student = $conn->prepare($sql_check_student);
    $stmt_check_student->bind_param("i", $student_id);
    $stmt_check_student->execute();
    $result_check_student = $stmt_check_student->get_result();

    if ($result_check_student->num_rows > 0) {
        $sql = "SELECT * FROM jobs";
        $result = $conn->query($sql);
    } else {
        $message = "Invalid student_id provided";
    }

    $stmt_check_student->close();
}
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    echo "<script>alert('$message');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Jobs</title>
    <link rel="stylesheet" href="nav_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"> <style>
    <style>
        .navbar-light .navbar-nav .nav-link {
    color:#000;
}
    </style>
</head>
<body>
<div>
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <a class="navbar-brand" href="index.html">Placement Portal</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
    <div class="container">
        <h2 class="mt-4 mb-4">View Jobs</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="student_id">Enter Student ID:</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <?php if (!empty($result) && $result->num_rows > 0): ?>
            <h3 class="mt-4">Available Jobs</h3>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="card-text">Location: <?php echo htmlspecialchars($row['location']); ?></p>
                                <p class="card-text">Salary: <?php echo htmlspecialchars($row['salary']); ?></p>
                                <p class="card-text">Start Date: <?php echo htmlspecialchars($row['start_date']); ?></p>
                                <p class="card-text">End Date: <?php echo htmlspecialchars($row['end_date']); ?></p>
                                <form action="apply_job.php" method="post">
                                    <input type="hidden" name="job_id" value="<?php echo $row['job_id']; ?>">
                                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                                    <button type="submit" class="btn btn-primary">Apply</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>