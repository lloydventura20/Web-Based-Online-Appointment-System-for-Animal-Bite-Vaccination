<?php
include '../db/connection.php';


$patient_id = isset($_POST['patient_id']) ? intval($_POST['patient_id']) : 0;

// Check if the patient ID is valid
if ($patient_id <= 0) {
    echo json_encode(array("error" => "Invalid patient ID"));
    exit();
}

// Prepare and execute the query to get patient data
$sql = "SELECT p.*, f.* 
        FROM patients p
        LEFT JOIN findings f ON p.patientid = f.patientid
        WHERE p.patientid = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array("error" => "Failed to prepare statement: " . $conn->error));
    exit();
}

$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any record found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Prepare the data to return
    $patientData = array(
        'firstname' => $row['firstname'],
        'midname' => $row['midname'],
        'lastname' => $row['lastname'],
        'sufix' => $row['sufix'],
        'date_of_birth' => $row['date_of_birth'],
        'age' => $row['age'],
        'place_of_birth' => $row['place_of_birth'],
        'gender' => $row['gender'],
        'cpnumber' => $row['cpnumber'],
        'email' => $row['email'],
        'civil_status' => $row['civil_status'],
        'guardian_name' => $row['guardian_name'],
        'nationality' => $row['nationality'],
        'religion' => $row['religion'],
        'occupation' => $row['occupation'],
        'barangay' => $row['barangay'],
        'municipal' => $row['municipal'],
        'province' => $row['province'],
        'region' => $row['region'],
        // Findings data
        'exposure' => $row['exposure'],
        'dose' => $row['dose'],
        'animal_type' => $row['animal_type'],
        'category' => $row['category'],
        'vaccine_type' => $row['vaccine_type'],
        'wound_type' => $row['wound_type'],
        'sob' => $row['sob'],
        'dob' => $row['dob'],
        'pob' => $row['pob'],
        'wound_wash' => $row['wound_wash'],
        'tandok' => $row['tandok'],
        'animal_class' => $row['animal_class'],
        'pcec' => $row['pcec'],
        'pvrv' => $row['pvrv'],
        'erig' => $row['erig'],
        'd1' => $row['d1'],
        'd3' => $row['d3'],
        'd7' => $row['d7'],
        'd2030' => $row['d2030'],
        'weight' => $row['weight'],
        'bp' => $row['bp'],
        'pr' => $row['pr'],
        'rr' => $row['rr'],
        'temp' => $row['temp'],
        'ats' => $row['ats']
    );

    // Output the data in JSON format
    echo json_encode($patientData);
} else {
    // No data found for the provided patient ID
    echo json_encode(array("error" => "No data found"));
}


$stmt->close();
$conn->close();
