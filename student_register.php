<?php
include("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'student';
    $query = "SELECT * FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "Username already taken.";
        } else {
          
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $insertQuery = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            if ($stmt = $conn->prepare($insertQuery)) {
                $stmt->bind_param('sss', $username, $passwordHash, $role);
                if ($stmt->execute()) {
                    header('Location: student_login.html');
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>
