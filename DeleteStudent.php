<?php
include("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $student_id = $_POST['id'];
        $conn->begin_transaction();
        try {
            $query1 = "DELETE FROM student_skills WHERE student_id = ?";
            if ($stmt1 = $conn->prepare($query1)) {
                $stmt1->bind_param("i", $student_id);
                $stmt1->execute();
                $stmt1->close();
            } else {
                throw new Exception("Error: Could not prepare statement for student_skills.");
            }
            $query2 = "DELETE FROM student_training WHERE student_id = ?";
            if ($stmt2 = $conn->prepare($query2)) {
                $stmt2->bind_param("i", $student_id);
                $stmt2->execute();
                $stmt2->close();
            } else {
                throw new Exception("Error: Could not prepare statement for student_training.");
            }

            $query3 = "DELETE FROM applications WHERE student_id = ?";
            if ($stmt3 = $conn->prepare($query3)) {
                $stmt3->bind_param("i", $student_id);
                $stmt3->execute();
                $stmt3->close();
            } else {
                throw new Exception("Error: Could not prepare statement for applications.");
            }
            $query4 = "DELETE FROM placements WHERE student_id = ?";
            if ($stmt4 = $conn->prepare($query4)) {
                $stmt4->bind_param("i", $student_id);
                $stmt4->execute();
                $stmt4->close();
            } else {
                throw new Exception("Error: Could not prepare statement for placement.");
            }
            $query5 = "DELETE FROM students WHERE student_id = ?";
            if ($stmt5 = $conn->prepare($query5)) {
                $stmt5->bind_param("i", $student_id);
                $stmt5->execute();
                
                if ($stmt5->affected_rows > 0) {
                    echo "Student deleted successfully.";
                } else {
                    throw new Exception("Error: Student not found.");
                }

                $stmt5->close();
            } else {
                throw new Exception("Error: Could not prepare statement for students.");
            }
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
        }
    } else {
        echo "Error: Student ID not set.";
    }
} else {
    echo "Error: Invalid request method.";
}

$conn->close();
?>
