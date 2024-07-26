<?php
include("database.php");

if(isset($_POST['id']) && !empty($_POST['id'])) {
    $company_id = $_POST['id'];

    $query = "DELETE FROM company WHERE company_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $company_id);
        if ($stmt->execute()) {
            echo "Company deleted successfully.";
        } else {
           
            echo "Error: Could not delete company. Please try again.";
        }
        $stmt->close();
    } else {
        
        echo "Error: Could not prepare statement for deletion.";
    }
} else {
    
    echo "Error: Company ID not provided.";
}

$conn->close();
?>
