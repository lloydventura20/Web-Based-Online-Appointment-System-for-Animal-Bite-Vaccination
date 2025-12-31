<?php
include '../../db/connection.php';
// Check if all fields are set
if (isset($_POST['firstname'], $_POST['midname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['role'])) {

    $firstname = $_POST['firstname'];
    $midname = $_POST['midname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password using PASSWORD_DEFAULT (currently bcrypt)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO user (firstname, midname, lastname, password, email, role) 
                            VALUES (?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("ssssss", $firstname, $midname, $lastname, $hashedPassword, $email, $role);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New user added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Please fill in all fields!";
}



