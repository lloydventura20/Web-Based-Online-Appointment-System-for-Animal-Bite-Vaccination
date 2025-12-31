<?php
require '../db/connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $midname = $_POST['midname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

  
    if (!empty($firstname) && !empty($midname) && !empty($lastname) && !empty($email) && !empty($password)) {
      
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
        $stmt = $conn->prepare("INSERT INTO user (firstname, midname, lastname, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $midname, $lastname, $email, $hashedPassword);

     
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $stmt->error; 
        }

        $stmt->close();
    } else {
        echo "Please fill all the fields."; 
    }
}

$conn->close();

