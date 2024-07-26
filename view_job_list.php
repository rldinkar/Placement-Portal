<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'company') {
    header("Location: company_login.html");
    exit();
}
include 'database.php';
$company_name = $_SESSION['username'];
$jobs = [];
$sql = "SELECT * FROM jobs WHERE company_name = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('s', $company_name);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }

    $stmt->close();
}
if (isset($_POST['delete_job'])) {
    $job_id_to_delete = $_POST['job_id_to_delete'];
    $conn->begin_transaction();

    try {
        $delete_job_sql = "DELETE FROM jobs WHERE job_id = ?";
        $delete_job_stmt = $conn->prepare($delete_job_sql);
        $delete_job_stmt->bind_param('i', $job_id_to_delete);
        $delete_job_stmt->execute();
        $delete_applications_sql = "DELETE FROM applications WHERE job_id = ?";
        $delete_applications_stmt = $conn->prepare($delete_applications_sql);
        $delete_applications_stmt->bind_param('i', $job_id_to_delete);
        $delete_applications_stmt->execute();
        $conn->commit();
        header("Location: view_job_list.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error deleting job: " . $e->getMessage();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Jobs</title>
    <link rel="stylesheet" href="nav_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
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
                        <a class="nav-link" href="company_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_job.php">Add Job</a>
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
    <div class="mt-5">
        <h1>Jobs</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jobs)): ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['title']); ?></td>
                            <td><?php echo htmlspecialchars($job['description']); ?></td>
                            <td><?php echo htmlspecialchars($job['location']); ?></td>
                            <td><?php echo htmlspecialchars($job['salary']); ?></td>
                            <td><?php echo htmlspecialchars($job['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($job['end_date']); ?></td>
                            <td class="action-buttons">
                                <form action="" method="POST">
                                    <input type="hidden" name="job_id_to_delete" value="<?php echo $job['job_id']; ?>">
                                    <button type="submit" name="delete_job" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job?')">Delete Job</button>
                                </form>
                                <a href='view_applications.php?job_id=<?php echo $job['job_id']; ?>' class='btn btn-info'>View Applications</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No jobs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
