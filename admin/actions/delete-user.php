<?php
include '../../db/connection.php'; // Include database connection

if (isset($_POST['userid'])) {
    $userid = $_POST['userid'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM user WHERE userid = ?");
    $stmt->bind_param('i', $userid);

    // Execute the statement
    if ($stmt->execute()) {
        echo "success"; // Return success response
    } else {
        echo "error"; // Return error response if something goes wrong
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "error"; // Return error if no user ID is sent
}

