<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db/connection.php'; 

if (isset($_POST['appointmentid'])) {
    $appointmentid = $_POST['appointmentid'];

    $sql = "UPDATE appointment SET status = 'approved' WHERE appointmentid = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("i", $appointmentid);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $stmt->error; 
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'invalid';
}

