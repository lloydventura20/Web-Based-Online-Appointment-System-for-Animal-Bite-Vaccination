<?php
include '../db/connection.php'; // Adjust the path to your database connection

$query = "SELECT p.patientid, CONCAT(p.firstname, ' ', p.lastname) AS patient_name
          FROM patient_que pq
          JOIN patients p ON pq.patientid = p.patientid
          WHERE pq.status = '2'";

$result = mysqli_query($conn, $query);
$data = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

