<?php
include '../db/connection.php';
header('Content-Type: application/json'); 



$requiredFields = [
    'firstname', 'midname', 'lastname', 'sufix', 'date_of_birth', 'age', 'place_of_birth', 'gender',
    'cpnumber', 'email', 'civil_status', 'guardian', 'nationality', 'religion', 'occupation', 
    'region', 'province', 'municipal', 'barangay', 'exposure','dose','animal_type', 'category', 'vaccine_type',
    'wound_type', 'sob', 'dob', 'pob', 'wound_wash', 'tandok', 'animal_class', 'pcec', 'pvrv', 
    'erig', 'd1', 'd3', 'd7', 'd2030', 'weight', 'bp', 'pr', 'rr', 'temp', 'ats'
];

foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
        exit;
    }
}


$firstname = $_POST['firstname'];
$midname = $_POST['midname'];
$lastname = $_POST['lastname'];
$sufix = $_POST['sufix'];
$date_of_birth = $_POST['date_of_birth'];
$age = $_POST['age'];
$place_of_birth = $_POST['place_of_birth'];
$gender = $_POST['gender'];
$cpnumber = $_POST['cpnumber'];
$email = $_POST['email'];
$civil_status = $_POST['civil_status'];
$guardian = $_POST['guardian'];
$nationality = $_POST['nationality'];
$religion = $_POST['religion'];
$occupation = $_POST['occupation'];
$region = $_POST['region'];
$province = $_POST['province'];
$municipal = $_POST['municipal'];
$barangay = $_POST['barangay'];

$exposure = $_POST['exposure'];
$dose = $_POST['dose'];
$animal_type = $_POST['animal_type'];
$category = $_POST['category'];
$vaccine_type = $_POST['vaccine_type'];
$wound_type = $_POST['wound_type'];
$sob = $_POST['sob'];
$dob = $_POST['dob'];
$pob = $_POST['pob'];
$wound_wash = $_POST['wound_wash'];
$tandok = $_POST['tandok'];
$animal_class = $_POST['animal_class'];
$pcec = $_POST['pcec'];
$pvrv = $_POST['pvrv'];
$erig = $_POST['erig'];
$d1 = $_POST['d1'];
$d3 = $_POST['d3'];
$d7 = $_POST['d7'];
$d2030 = $_POST['d2030'];
$weight = $_POST['weight'];
$bp = $_POST['bp'];
$pr = $_POST['pr'];
$rr = $_POST['rr'];
$temp = $_POST['temp'];
$ats = $_POST['ats'];

$conn->begin_transaction();

try {
   
    $stmt = $conn->prepare("INSERT INTO patients (firstname, 
                                                midname, 
                                                lastname, 
                                                sufix, 
                                                date_of_birth, 
                                                age, 
                                                place_of_birth, 
                                                gender, 
                                                cpnumber, 
                                                email, 
                                                civil_status,
                                                 guardian_name, 
                                                 nationality, 
                                                 religion, 
                                                 occupation, 
                                                 barangay, 
                                                 municipal, 
                                                 province, 
                                                 region) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssssssss", $firstname, $midname, $lastname, $sufix, $date_of_birth, $age, $place_of_birth, $gender, $cpnumber, $email, $civil_status, $guardian, $nationality, $religion, $occupation, $barangay, $municipal, $province, $region);
    $stmt->execute();
    $patientid = $conn->insert_id; // Get the last inserted patient ID

    // Insert findings data
    $stmt = $conn->prepare("INSERT INTO findings (patientid,
                                                exposure,
                                                dose,
                                                animal_type, 
                                                category, 
                                                vaccine_type, 
                                                wound_type, 
                                                sob, 
                                                dob, 
                                                pob, 
                                                wound_wash, 
                                                tandok, 
                                                animal_class, 
                                                pcec, 
                                                pvrv, 
                                                erig, 
                                                d1, 
                                                d3, 
                                                d7, 
                                                d2030,
                                                 weight, 
                                                 bp, 
                                                 pr, 
                                                 rr, 
                                                 temp, 
                                                 ats) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssssssssssssssssss", $patientid,$exposure,$dose, $animal_type, $category, $vaccine_type, $wound_type, $sob, $dob, $pob, $wound_wash, $tandok, $animal_class, $pcec, $pvrv, $erig, $d1, $d3, $d7, $d2030, $weight, $bp, $pr, $rr, $temp, $ats);
    $stmt->execute();
    $findingid = $conn->insert_id; 

   
    $stmt = $conn->prepare("INSERT INTO patient_que (patientid, findingsid, status) VALUES (?, ?, ?)");
    $status = '0';
    $stmt->bind_param("iis", $patientid, $findingid, $status);
    $stmt->execute();

    $conn->commit();

 
    echo json_encode(['status' => 'success', 'message' => 'Patient walk-in information saved successfully!']);
} catch (Exception $e) {
    $conn->rollback();

    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();

