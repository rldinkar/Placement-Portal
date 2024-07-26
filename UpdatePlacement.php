<?php
include("database.php");

if (
    isset($_POST['placement_id']) &&
    isset($_POST['first_name']) &&
    isset($_POST['last_name']) &&
    isset($_POST['company_name']) &&
    isset($_POST['role']) &&
    isset($_POST['placement_date']) &&
    isset($_POST['placement_type'])
) {
    $placementId = mysqli_real_escape_string($conn, $_POST['placement_id']);
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $companyName = mysqli_real_escape_string($conn, $_POST['company_name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $placementDate = mysqli_real_escape_string($conn, $_POST['placement_date']);
    $placementType = mysqli_real_escape_string($conn, $_POST['placement_type']);
    mysqli_begin_transaction($conn);

    try {
        $studentQuery = "SELECT student_id FROM placements WHERE placement_id=?";
        $studentStmt = mysqli_prepare($conn, $studentQuery);
        if (!$studentStmt) {
            throw new Exception("Error preparing student query: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($studentStmt, 'i', $placementId);
        mysqli_stmt_execute($studentStmt);
        $studentResult = mysqli_stmt_get_result($studentStmt);

        if ($studentRow = mysqli_fetch_assoc($studentResult)) {
            $studentId = $studentRow['student_id'];
            $updateStudentQuery = "UPDATE students SET first_name=?, last_name=? WHERE student_id=?";
            $updateStudentStmt = mysqli_prepare($conn, $updateStudentQuery);
            if (!$updateStudentStmt) {
                throw new Exception("Error preparing student update query: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($updateStudentStmt, 'ssi', $firstName, $lastName, $studentId);
            if (!mysqli_stmt_execute($updateStudentStmt)) {
                throw new Exception("Error updating student details: " . mysqli_error($conn));
            }
            mysqli_stmt_close($updateStudentStmt);
            $companyQuery = "SELECT company_id FROM company WHERE company_name=?";
            $companyStmt = mysqli_prepare($conn, $companyQuery);
            if (!$companyStmt) {
                throw new Exception("Error preparing company query: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($companyStmt, 's', $companyName);
            mysqli_stmt_execute($companyStmt);
            $companyResult = mysqli_stmt_get_result($companyStmt);

            if ($companyRow = mysqli_fetch_assoc($companyResult)) {
                $companyId = $companyRow['company_id'];
            } else {
                $insertCompanyQuery = "INSERT INTO company (company_name) VALUES (?)";
                $insertCompanyStmt = mysqli_prepare($conn, $insertCompanyQuery);
                if (!$insertCompanyStmt) {
                    throw new Exception("Error preparing company insert query: " . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($insertCompanyStmt, 's', $companyName);
                if (mysqli_stmt_execute($insertCompanyStmt)) {
                    $companyId = mysqli_insert_id($conn);
                } else {
                    throw new Exception("Error inserting new company: " . mysqli_error($conn));
                }
                mysqli_stmt_close($insertCompanyStmt);
            }
            mysqli_stmt_close($companyStmt);
            $updatePlacementQuery = "UPDATE placements SET student_id=?, company_id=?, role=?, placement_date=?, placement_type=? WHERE placement_id=?";
            $updatePlacementStmt = mysqli_prepare($conn, $updatePlacementQuery);
            if (!$updatePlacementStmt) {
                throw new Exception("Error preparing placement update query: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($updatePlacementStmt, 'iisssi', $studentId, $companyId, $role, $placementDate, $placementType, $placementId);

            if (!mysqli_stmt_execute($updatePlacementStmt)) {
                throw new Exception("Error updating placement details: " . mysqli_error($conn));
            }
            mysqli_stmt_close($updatePlacementStmt);
            mysqli_commit($conn);
            echo "Placement details updated successfully.";
        } else {
            throw new Exception("Error: Student not found.");
        }

        mysqli_stmt_close($studentStmt);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Failed to update placement details: " . $e->getMessage();
    }
} else {
    echo "Incomplete data provided.";
}

mysqli_close($conn);
?>
