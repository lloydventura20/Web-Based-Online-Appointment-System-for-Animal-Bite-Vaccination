<?php
session_start();
require '../db/connection.php'; 

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the statement to fetch user information based on the email
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Check if the user has the role of 'admin'
            if ($row['role'] === 'admin') {
                // Store user info in the session for admin login
                $_SESSION['adminUserId'] = $row['userid'];
                $_SESSION['adminfirstname'] = $row['firstname'];
                $_SESSION['adminrole'] = $row['role'];
                
                echo "success"; // This could redirect to an admin dashboard page
            } else {
                echo "You are not authorized to log in as an admin.";
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    $stmt->close();
} else {
    echo "Please provide email and password.";
}
$conn->close();
