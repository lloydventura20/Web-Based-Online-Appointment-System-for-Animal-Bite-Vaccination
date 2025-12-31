<?php
include '../../db/connection.php';

if (isset($_POST['userid'])) {
    
    $userid = $_POST['userid'];
    $firstname = $_POST['firstname'];
    $midname = $_POST['midname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

   
    if ($password) {
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE user SET firstname=?, midname=?, lastname=?, email=?, password=? WHERE userid=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $firstname, $midname, $lastname, $email, $hashedPassword, $userid);
    } else {

        $query = "UPDATE user SET firstname=?, midname=?, lastname=?, email=? WHERE userid=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $firstname, $midname, $lastname, $email, $userid);
    }

 
    if ($stmt->execute()) {
       
        echo json_encode(['status' => 'success']);
    } else {
       
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }


    $stmt->close();
}

$conn->close();

