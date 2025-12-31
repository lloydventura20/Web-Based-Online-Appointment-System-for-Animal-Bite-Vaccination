<?php
include '../db/connection.php'; // Include your database connection file

if (isset($_POST['queid'])) {
    $queid = intval($_POST['queid']); // Get Queue ID from POST data

    // Update the status in the database
    $query = "UPDATE patient_que SET status = '0' WHERE queid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i",$queid);

    if ($stmt->execute()) {
        echo 'success'; // Return success if the update was successful
    } else {
        echo 'error'; // Return error if the update failed
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'error'; // Return error if queid or status is not set
}

