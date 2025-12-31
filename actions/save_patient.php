<?php
include '../db/connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function updatePatient($conn, $patientid, $data) {
    $stmt = $conn->prepare("UPDATE patients SET firstname = ?, midname = ?, lastname = ?, sufix = ?, date_of_birth = ?, age = ?, place_of_birth = ?, gender = ?, cpnumber = ?, email = ?, civil_status = ?, guardian_name = ?, nationality = ?, religion = ?, occupation = ?, barangay = ?, municipal = ?, province = ?, region = ? WHERE patientid = ?");
    
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

function insertFindings($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO findings (patientid,exposure,dose ,animal_type, category, vaccine_type, wound_type, sob, dob, pob, wound_wash, tandok, animal_class, pcec, pvrv, erig, d1, d3, d7, d2030, weight, bp, pr, rr, temp, ats) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("isssssssssssssssssssssssss", 
        $data['patientid'],
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
        $data['d20/30'], 
        $data['weight'], 
        $data['bp'], 
        $data['pr'], 
        $data['rr'], 
        $data['temp'], 
        $data['ats']
    );

    if ($stmt->execute()) {
        echo "New findings inserted successfully.";
        return $conn->insert_id; 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    return null;
}

function insertPatientQueue($conn, $patientid, $findingsid) {
    $stmt = $conn->prepare("INSERT INTO patient_que (patientid, findingsid, status) VALUES (?, ?, '0')");
    
    $stmt->bind_param("ii", $patientid, $findingsid);

    if ($stmt->execute()) {
        echo "Patient queue updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

function updateAppointmentStatusToDone($conn, $appointment_id) {
    $stmt = $conn->prepare("UPDATE appointment SET status = 'done' WHERE appointmentid = ?");
    
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        echo "Appointment status updated to 'done' successfully.";
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
        'd20/30' => $_POST['d20/30'] ?? '',
        'weight' => $_POST['weight'] ?? '',
        'bp' => $_POST['bp'] ?? '',
        'pr' => $_POST['pr'] ?? '',
        'rr' => $_POST['rr'] ?? '',
        'temp' => $_POST['temp'] ?? '',
        'ats' => $_POST['ats'] ?? '',
    ];

    $patientid = $_POST['patientid']; 
    $appointment_id = $_POST['appointment_id'];  // Get appointment ID from POST data

    // Update patient details
    updatePatient($conn, $patientid, $patientData);

    // Insert new findings
    $findingsid = insertFindings($conn, $findingsData);

    if ($findingsid) {
        // Insert patient into queue
        insertPatientQueue($conn, $patientid, $findingsid);
        
        // Update appointment status to 'done'
        updateAppointmentStatusToDone($conn, $appointment_id);
    }

    // Redirect to consult.php after processing
    header("Location: ../consult.php");
    exit();
}

$conn->close();

