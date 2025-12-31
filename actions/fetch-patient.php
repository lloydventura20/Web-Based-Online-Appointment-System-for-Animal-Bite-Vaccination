<?php
include '../db/connection.php'; // Adjust this to your database connection file

if (isset($_POST['query'])) {
    $query = $_POST['query'];

    // Search query (ID or Name)
    $sql = "SELECT * FROM patients 
            LEFT JOIN findings ON patients.patientid = findings.patientid
            WHERE patients.patientid = ? OR CONCAT(firstname, ' ', lastname) LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $query, $likeQuery);
    $likeQuery = "%$query%";
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['status' => 'not_found']);
    }
}
