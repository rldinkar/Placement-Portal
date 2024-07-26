<?php
session_start();
include("database.php");

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;
    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;

    $sql_check_job = "SELECT job_id FROM jobs WHERE job_id = ?";
    $stmt_check_job = $conn->prepare($sql_check_job);
    $stmt_check_job->bind_param("i", $job_id);
    $stmt_check_job->execute();
    $result_check_job = $stmt_check_job->get_result();

    if ($result_check_job->num_rows > 0) {
        $sql_check_student = "SELECT student_id FROM students WHERE student_id = ?";
        $stmt_check_student = $conn->prepare($sql_check_student);
        $stmt_check_student->bind_param("i", $student_id);
        $stmt_check_student->execute();
        $result_check_student = $stmt_check_student->get_result();

        if ($result_check_student->num_rows > 0) {
            $sql_check_application = "SELECT * FROM applications WHERE job_id = ? AND student_id = ?";
            $stmt_check_application = $conn->prepare($sql_check_application);
            $stmt_check_application->bind_param("ii", $job_id, $student_id);
            $stmt_check_application->execute();
            $result_check_application = $stmt_check_application->get_result();

            if ($result_check_application->num_rows == 0) {
                $sql_insert = "INSERT INTO applications (job_id, student_id) VALUES (?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("ii", $job_id, $student_id);

                if ($stmt_insert->execute()) {
                    $message = "Job application submitted successfully";
                } else {
                    $message = "Error executing SQL statement: " . $stmt_insert->error;
                }

                $stmt_insert->close();
            } else {
                $message = "You have already applied for this job";
            }

            $stmt_check_application->close();
        } else {
            $message = "Invalid student_id provided";
        }

        $stmt_check_student->close();
    } else {
        $message = "Invalid job_id provided";
    }

    $stmt_check_job->close();
} else {
    $message = "Invalid request method";
}

$conn->close();

header("Location: view_jobs.php?message=" . urlencode($message));
exit();
?>
