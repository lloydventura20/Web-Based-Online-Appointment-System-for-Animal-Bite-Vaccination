<?php
header('Content-Type: application/json');
include '../db/connection.php'; 

$query = "
    SELECT p.patientid, p.firstname, p.lastname,p.age, p.barangay,p.municipal, pq.status
    FROM patient_que pq
    JOIN patients p ON pq.patientid = p.patientid
    WHERE pq.status = '1'
";

$result = $conn->query($query);

$patients = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

echo json_encode($patients);
