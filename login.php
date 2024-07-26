<?php
session_start();
include("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    switch ($user['role']) {
                        case 'admin':
                            header("Location: admin_dashboard.php");
                            exit();
                        case 'student':
                            header("Location: student_dashboard.php");
                            exit();
                        case 'company':
                            header("Location: company_dashboard.php");
                            exit();
                        default:
                            echo "Invalid role.";
                            break;
                    }
                } else {
                    echo "Invalid password.";
                }
            } else {
                echo "No user found with this username.";
            }
        } else {
            echo "Database error: " . $conn->error;
        }
    } else {
        echo "Please fill out all fields.";
    }
} else {
    echo "Invalid request.";
}
?>
