<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.html");
    exit();
}

require_once 'database.php'; 

$studentCountResult = $conn->query("SELECT COUNT(*) as count, MAX(cgpa) as highest_cgpa FROM students");
$studentCountData = $studentCountResult->fetch_assoc();
$studentCount = $studentCountData['count'];
$highestCgpa = $studentCountData['highest_cgpa'];

$companyCountResult = $conn->query("SELECT COUNT(*) as count, MAX(package) as highest_package FROM company");
$companyCountData = $companyCountResult->fetch_assoc();
$companyCount = $companyCountData['count'];
$highestCompanyPackage = $companyCountData['highest_package'];

$placementCountResult = $conn->query("SELECT COUNT(*) as count FROM placements");
$placementCountData = $placementCountResult->fetch_assoc();
$placementCount = $placementCountData['count'];


$trainingProgramCountResult = $conn->query("SELECT COUNT(*) as count, MAX(start_date) as latest_program_date FROM training_programs");
$trainingProgramCountData = $trainingProgramCountResult->fetch_assoc();
$trainingProgramCount = $trainingProgramCountData['count'];
$latestTrainingProgramDate = $trainingProgramCountData['latest_program_date'];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href = "nav_styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <style>
        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }
        .card {
            margin: 10px;
            flex: 1 1 calc(25% - 20px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .card h5 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #fff;
            background-color: #000; 
            padding: 10px;
            text-align: center;
            border-bottom: 2px solid #ff69b4; 
        }
        .card p {
            margin-bottom: 0.5rem;
            color: #333; 
            text-align: center;
            font-size: 1.4rem;
            font-weight: bold; 
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
    <a class="navbar-brand" href="#">Placement Portal</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="add_student.html">Add Student</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_company.html">Add Company</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_placement.html">Add Placement</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_training_program.html">Add Programs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="welcome-message">
    <?php echo "<h1>Welcome, Admin " . htmlspecialchars($_SESSION['username']) . "</h1>"; ?>
</div>

<div class="dashboard-cards">
    <div class="card">
        <div class="card-body center-btn">
            <h5 class="card-title">Students</h5>
            <p class="card-text">Total: <?php echo $studentCount; ?></p>
            <p class="card-text">Highest CGPA: <?php echo $highestCgpa; ?></p>
            <a href="read_student.php" class="btn btn-primary">See records</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body center-btn">
            <h5 class="card-title">Companies</h5>
            <p class="card-text">Total: <?php echo $companyCount; ?></p>
            <p class="card-text">Highest Package: <?php echo $highestCompanyPackage; ?></p>
            <a href="read_company.php" class="btn btn-primary">See records</a>

        </div>
    </div>
    <div class="card">
        <div class="card-body center-btn">
            <h5 class="card-title">Placements</h5>
            <br>
            <p class="card-text">Placed Students: <?php echo $placementCount; ?></p>
            <br>
            <a href="read_placement.php" class="btn btn-primary">See records</a>

    </div>
    </div>
    <div class="card">
        <div class="card-body center-btn">
            <h5 class="card-title">Training Programs</h5>
            <p class="card-text">Total: <?php echo $trainingProgramCount; ?></p>
            <p class="card-text">Latest Program: <?php echo $latestTrainingProgramDate; ?></p>
            <a href="read_training_program.php" class="btn btn-primary">See records</a>

        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
