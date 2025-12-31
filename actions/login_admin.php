<?php
session_start();
require '../db/connection.php'; 

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

 

   
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        
        if (password_verify($password, $row['password'])) {
        
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['firstname'] = $row['firstname'];
           
            echo "success";
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
