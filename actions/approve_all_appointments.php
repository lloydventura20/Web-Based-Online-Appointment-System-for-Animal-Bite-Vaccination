<?php
// Database connection
include '../db/connection.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure that IDs are passed
    if (!empty($_POST['ids'])) {
        $ids = $_POST['ids'];
        $placeholders = implode(',', array_fill(0, count($ids), '?')); // Create placeholders for prepared statement

        // SQL query to update status
        $sql = "UPDATE appointment SET status = 'approved' WHERE appointmentid IN ($placeholders)";

        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids); // Bind integer parameters for each ID
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo 'success';
            } else {
                echo 'No records updated';
            }

            $stmt->close();
        } else {
            echo 'SQL error: ' . $conn->error;
        }
    } else {
        echo 'No IDs provided';
    }
} else {
    echo 'Invalid request method';
}

$conn->close();
