<?php
include '../db/connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function updatePatient($conn, $patientid, $data) {
    $stmt = $conn->prepare("UPDATE patients SET firstname = ?, 
                                                midname = ?, 
                                                lastname = ?, 
                                                sufix = ?, 
                                                date_of_birth = ?, 
                                                age = ?, 
                                                place_of_birth = ?, 
                                                gender = ?, 
                                                cpnumber = ?, 
                                                email = ?, 
                                                civil_status = ?, 
                                                guardian_name = ?, 
                                                nationality = ?, 
                                                religion = ?, 
                                                occupation = ?, 
                                                barangay = ?, 
                                                municipal = ?, 
                                                province = ?, 
                                                region = ? 
                                                WHERE patientid = ?");
    
    $stmt->bind_param("sssssssssssssssssssi", 
        $data['firstname'], 
        $data['midname'], 
        $data['lastname'], 
        $data['sufix'], 
        $data['date_of_birth'], 
        $data['age'], 
        $data['place_of_birth'], 
        $data['gender'], 
        $data['cpnumber'], 
        $data['email'], 
        $data['civil_status'], 
        $data['guardian_name'], 
        $data['nationality'], 
        $data['religion'], 
        $data['occupation'], 
        $data['barangay'], 
        $data['municipal'], 
        $data['province'], 
        $data['region'], 
        $patientid
    );

    if ($stmt->execute()) {
        echo "Patient updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

function updateFindings($conn, $data) {
    $stmt = $conn->prepare("UPDATE findings SET exposure = ?,
                                                dose = ?, 
                                                animal_type = ?, 
                                                category = ?, 
                                                vaccine_type = ?, 
                                                wound_type = ?, 
                                                sob = ?, 
                                                dob = ?, 
                                                pob = ?, 
                                                wound_wash = ?, 
                                                tandok = ?, 
                                                animal_class = ?, 
                                                pcec = ?, 
                                                pvrv = ?, 
                                                erig = ?, 
                                                d1 = ?, 
                                                d3 = ?, 
                                                d7 = ?, 
                                                d2030 = ?, 
                                                weight = ?, 
                                                bp = ?, 
                                                pr = ?, 
                                                rr = ?, 
                                                temp = ?, 
                                                ats = ? 
                                                WHERE patientid = ?");

    $stmt->bind_param("sssssssssssssssssssssssssi",
        $data['exposure'],
        $data['dose'],
        $data['animal_type'], 
        $data['category'], 
        $data['vaccine_type'], 
        $data['wound_type'], 
        $data['sob'], 
        $data['dob'], 
        $data['pob'], 
        $data['wound_wash'], 
        $data['tandok'], 
        $data['animal_class'], 
        $data['pcec'], 
        $data['pvrv'], 
        $data['erig'], 
        $data['d1'], 
        $data['d3'], 
        $data['d7'], 
        $data['d2030'], 
        $data['weight'], 
        $data['bp'], 
        $data['pr'], 
        $data['rr'], 
        $data['temp'], 
        $data['ats'],
        $data['patientid']
    );

    if ($stmt->execute()) {
        echo "Findings updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

function resetPatientQueueStatus($conn, $patientid) {
    $stmt = $conn->prepare("UPDATE patient_que SET status = '0', updated_at = NOW() WHERE patientid = ?");
    
    $stmt->bind_param("i", $patientid);

    if ($stmt->execute()) {
        echo "Patient queue status reset successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['patientid']) || empty($_POST['patientid'])) {
        die("Error: Patient ID is missing.");
    }

    $patientData = [
        'firstname' => $_POST['firstname'] ?? '',
        'midname' => $_POST['midname'] ?? '',
        'lastname' => $_POST['lastname'] ?? '',
        'sufix' => $_POST['sufix'] ?? '',
        'date_of_birth' => $_POST['date_of_birth'] ?? '',
        'age' => $_POST['age'] ?? '',
        'place_of_birth' => $_POST['place_of_birth'] ?? '',
        'gender' => $_POST['gender'] ?? '',
        'cpnumber' => $_POST['cpnumber'] ?? '',
        'email' => $_POST['email'] ?? '',
        'civil_status' => $_POST['civil_status'] ?? '',
        'guardian_name' => $_POST['guardian_name'] ?? '',
        'nationality' => $_POST['nationality'] ?? '',
        'religion' => $_POST['religion'] ?? '',
        'occupation' => $_POST['occupation'] ?? '',
        'barangay' => $_POST['barangay'] ?? '',
        'municipal' => $_POST['municipal'] ?? '',
        'province' => $_POST['province'] ?? '',
        'region' => $_POST['region'] ?? '',
    ];

    $findingsData = [
        'patientid' => $_POST['patientid'], 
        'exposure' => $_POST['exposure'], 
        'dose' => $_POST['dose'], 
        'animal_type' => $_POST['animal_type'] ?? '',
        'category' => $_POST['category'] ?? '',
        'vaccine_type' => $_POST['vaccine_type'] ?? '',
        'wound_type' => $_POST['wound_type'] ?? '',
        'sob' => $_POST['sob'] ?? '',
        'dob' => $_POST['dob'] ?? '',
        'pob' => $_POST['pob'] ?? '',
        'wound_wash' => $_POST['wound_wash'] ?? '',
        'tandok' => $_POST['tandok'] ?? '',
        'animal_class' => $_POST['animal_class'] ?? '',
        'pcec' => $_POST['pcec'] ?? '',
        'pvrv' => $_POST['pvrv'] ?? '',
        'erig' => $_POST['erig'] ?? '',
        'd1' => $_POST['d1'] ?? '',
        'd3' => $_POST['d3'] ?? '',
        'd7' => $_POST['d7'] ?? '',
        'd2030' => $_POST['d2030'] ?? '',
        'weight' => $_POST['weight'] ?? '',
        'bp' => $_POST['bp'] ?? '',
        'pr' => $_POST['pr'] ?? '',
        'rr' => $_POST['rr'] ?? '',
        'temp' => $_POST['temp'] ?? '',
        'ats' => $_POST['ats'] ?? '',
    ];

    $patientid = $_POST['patientid'];

    // Update patient details
    updatePatient($conn, $patientid, $patientData);

    // Update findings
    updateFindings($conn, $findingsData);

    // Reset patient queue status to 0
    resetPatientQueueStatus($conn, $patientid);

    // Redirect to patient record page after processing
    header("Location: ../patient-record.php");
    exit();
}

$conn->close();

