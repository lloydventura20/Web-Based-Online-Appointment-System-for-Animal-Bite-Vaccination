<?php
include '../db/connection.php'; 

if (isset($_POST['patient_id'])) { 
    $patientId = $_POST['patient_id'];

    $sql = "SELECT p.firstname, p.midname, p.lastname, p.sufix, p.date_of_birth, p.age, p.place_of_birth, p.gender, p.cpnumber, p.email, p.civil_status, p.guardian_name, p.nationality, p.religion, p.occupation, p.barangay, p.municipal, p.province, p.region,
                   a.appointment_day, a.appointment_date, a.status AS appointment_status
            FROM patients p
            LEFT JOIN appointment a ON p.patientid = a.patientid
            WHERE p.patientid = ?"; 

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $patientData = $result->fetch_assoc();

    echo json_encode($patientData);
}

$conn->close();
