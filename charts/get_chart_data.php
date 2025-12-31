<?php
include '../db/connection.php';

// Query to get the count of 'Bite' and 'Scratch' incidents by 'pob' and 'animal_type'
$sql_pob_animal_type = "
    SELECT 
        pob,
        animal_type,
        SUM(CASE WHEN wound_type = 'Bite' THEN 1 ELSE 0 END) AS total_bite,
        SUM(CASE WHEN wound_type = 'Scratch' THEN 1 ELSE 0 END) AS total_scratch
    FROM findings
    GROUP BY pob, animal_type
";

$result_pob = $conn->query($sql_pob_animal_type);

$data = [];

if ($result_pob->num_rows > 0) {
    while ($row = $result_pob->fetch_assoc()) {
        $data[] = [
            'pob' => $row['pob'],
            'animal_type' => $row['animal_type'],
            'total_bite' => $row['total_bite'],
            'total_scratch' => $row['total_scratch']
        ];
    }
}

// Return data as JSON
echo json_encode($data);

$conn->close();
