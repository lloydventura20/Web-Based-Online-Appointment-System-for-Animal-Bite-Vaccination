<?php
include '../db/connection.php'; // Include your database connection here

$response = [];

if (isset($_GET['patientid'])) {
    $patientid = $_GET['patientid'];

    $sql = "DELETE FROM patients WHERE patientid = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $patientid);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Patient deleted successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error deleting patient. Please try again.';
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error preparing the deletion. Please try again.';
    }

    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

// Return JSON response
echo json_encode($response);

