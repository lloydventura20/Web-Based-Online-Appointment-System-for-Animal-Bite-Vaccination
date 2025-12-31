<?php 
session_start();
include '../db/connection.php'; 

$email = $_POST['email'] ?? '';

if (!empty($email)) {
    
    $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Error: Email already exists.";
    } else {
        echo "Success: Email is available.";
    }

    $stmt->close();
} else {
    echo "Error: Email field is empty.";
}

$conn->close();

