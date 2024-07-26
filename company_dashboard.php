<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'company') {
    header("Location: company_login.html");
    exit();
}

include 'database.php';


$company_name = $conn->real_escape_string($_SESSION['username']);
$jobs_result = $conn->query("SELECT COUNT(*) AS job_count FROM jobs WHERE company_name = '$company_name'");
$job_count = $jobs_result->fetch_assoc()['job_count'];

$applications_result = $conn->query("SELECT COUNT(*) AS application_count FROM applications WHERE job_id IN (SELECT job_id FROM jobs WHERE company_name = '$company_name')");
$application_count = $applications_result->fetch_assoc()['application_count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link rel="stylesheet" href="nav_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
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

<div class="welcome-message">
    <?php echo "<h1>Welcome, Company " . htmlspecialchars($_SESSION['username']) . "</h1>"; ?>
</div>


<div class="dashboard-cards">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Jobs Posted</h5>
                <p class="card-text"><?php echo $job_count; ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Applications Received</h5>
                <p class="card-text"><?php echo $application_count; ?></p>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
