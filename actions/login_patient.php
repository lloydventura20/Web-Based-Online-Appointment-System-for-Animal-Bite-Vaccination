<?php
session_start(); // Start the session

include '../db/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs
    $email = $conn->real_escape_string($_POST['email']);
    $cpnumber = $conn->real_escape_string($_POST['cpnumber']);

    // Prepare SQL query with placeholders
    $stmt = $conn->prepare('SELECT patientid, firstname,lastname FROM patients WHERE email = ? AND cpnumber = ?');
    
    // Bind the input parameters to the placeholders
    $stmt->bind_param('ss', $email, $cpnumber);
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user's data
        $patient = $result->fetch_assoc();
        
   
        $_SESSION['patientid'] = $patient['patientid'];
        $_SESSION['fname'] = $patient['firstname'];
        $_SESSION['lname'] = $patient['lastname'];

   
        echo json_encode(['success' => true]);
    } else {

        echo json_encode(['success' => false, 'message' => 'Invalid email or cellphone number.']);
    }

 
    $stmt->close();
}


$conn->close();

