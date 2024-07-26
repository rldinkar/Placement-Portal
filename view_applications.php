<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'company') {
    header("Location: company_login.html");
    exit();
}
include 'database.php';
$message = '';
$applications = [];
$job = null;

if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    $sql_job = "SELECT * FROM jobs WHERE job_id = ?";
    $stmt_job = $conn->prepare($sql_job);
    $stmt_job->bind_param('i', $job_id);
    $stmt_job->execute();
    $result_job = $stmt_job->get_result();

    if ($result_job->num_rows == 1) {
        $job = $result_job->fetch_assoc();
        $sql_applications = "SELECT a.*, s.first_name, s.middle_name, s.last_name, s.email, s.mobile_no, s.gender, s.course, s.year, s.cgpa
                             FROM applications a
                             INNER JOIN students s ON a.student_id = s.student_id
                             WHERE a.job_id = ?";
        $stmt_applications = $conn->prepare($sql_applications);
        $stmt_applications->bind_param('i', $job_id);
        $stmt_applications->execute();
        $result_applications = $stmt_applications->get_result();
        while ($row = $result_applications->fetch_assoc()) {
            $applications[] = $row;
        }

        $stmt_applications->close();
    } else {
        $message = "No job found with the provided job_id.";
    }

    $stmt_job->close();
} else {
    $message = "No job_id provided.";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nav_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <style>
        .navbar-light .navbar-nav .nav-link {
    color:#000;
    }
    p
    {
        font-size:25px;
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
                        <a class="nav-link" href="company_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_job.php">Add Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_job_list.php">View Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
<div class="container mt-5">
    <?php if ($job): ?>
        <h1>Applications: <?php echo htmlspecialchars($job['title']); ?></h1>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
    <?php else: ?>
        <h2>No Job Found</h2>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($applications)): ?>
        <h3>Applications:</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile No</th>
                    <th>Gender</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>CGPA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['middle_name'] . ' ' . $application['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($application['email']); ?></td>
                        <td><?php echo htmlspecialchars($application['mobile_no']); ?></td>
                        <td><?php echo htmlspecialchars($application['gender']); ?></td>
                        <td><?php echo htmlspecialchars($application['course']); ?></td>
                        <td><?php echo htmlspecialchars($application['year']); ?></td>
                        <td><?php echo htmlspecialchars($application['cgpa']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
