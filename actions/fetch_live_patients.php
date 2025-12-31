<?php
include '../db/connection.php'; // Include your database connection

$query = "SELECT pq.patientid FROM patient_que pq WHERE pq.status = '2'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$patients = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $patients[] = $row['patientid']; // Only return the patientid
    }
}

header('Content-Type: application/json');
echo json_encode($patients);
