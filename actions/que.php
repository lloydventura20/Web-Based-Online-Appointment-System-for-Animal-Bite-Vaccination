<?php
include '../db/connection.php'; 


$sql = "
    SELECT p.patientid, p.firstname, p.midname, p.lastname, p.age, p.gender 
    FROM patients p
    JOIN patient_que pq ON p.patientid = pq.patientid
    WHERE pq.status = '0'
";
$result = $conn->query($sql); 


$age_groups = [
    '0-12' => [],
    '13-59' => [],
    '60-above' => []
];

// Fetch and categorize patients
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $age = intval($row['age']);
        
        // Categorize based on age
        if ($age >= 0 && $age <= 12) {
            $age_groups['0-12'][] = $row;
        } elseif ($age >= 13 && $age <= 59) {
            $age_groups['13-59'][] = $row;
        } elseif ($age >= 60) {
            $age_groups['60-above'][] = $row;
        }
    }
}

$conn->close(); 


echo json_encode($age_groups);

