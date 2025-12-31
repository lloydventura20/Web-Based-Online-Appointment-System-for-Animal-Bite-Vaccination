<?php 
session_start();
include '../db/connection.php'; 


echo "<pre>";
print_r($_POST);
echo "</pre>";

$firstname = $_POST['firstname'] ?? '';
$midname = $_POST['midname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$sufix = $_POST['sufix'] ?? '';
$date_of_birth = $_POST['date_of_birth'] ?? '';
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$civil_status = $_POST['civil_status'] ?? '';
$nationality = $_POST['nationality'] ?? '';
$religion = $_POST['religion'] ?? '';
$occupation = $_POST['occupation'] ?? '';
$guardian = $_POST['guardian'] ?? '';
$cpnumber = $_POST['cpnumber'] ?? '';
$email = $_POST['email'] ?? '';
$region = $_POST['region'] ?? '';
$province = $_POST['province'] ?? '';
$municipal = $_POST['municipal'] ?? '';
$barangay = $_POST['barangay'] ?? '';
$place_of_birth = $_POST['place_of_birth'] ?? '';
$dayPicker = $_POST['dayPicker'] ?? '';
$datePicker = $_POST['datePicker'] ?? '';


if (is_numeric($dayPicker) && !empty($dayPicker)) {

    $stmt = $conn->prepare("
        INSERT INTO patients (
            firstname, midname, lastname, sufix, date_of_birth, age, place_of_birth, gender, 
            cpnumber, email, civil_status, guardian_name, nationality, religion, occupation, 
            barangay, municipal, province, region
        ) VALUES (
            ?, ?, ?, ?, STR_TO_DATE(?, '%m/%d/%Y'), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmt->bind_param(
        "sssssssssssssssssss", 
        $firstname, $midname, $lastname, $sufix, $date_of_birth, $age, $place_of_birth, $gender, 
        $cpnumber, $email, $civil_status, $guardian, $nationality, $religion, $occupation, 
        $barangay, $municipal, $province, $region
    );

    if ($stmt->execute()) {
        $patientid = $stmt->insert_id;


        $stmt_appointment = $conn->prepare("
            INSERT INTO appointment (patientid, appointment_day, appointment_date)
            VALUES (?, ?, STR_TO_DATE(?, '%m/%d/%Y'))
        ");

        $stmt_appointment->bind_param("iss", $patientid, $dayPicker, $datePicker);

        if ($stmt_appointment->execute()) {
            echo "New patient record and appointment created successfully.";
        } else {
            echo "Error saving appointment: " . $stmt_appointment->error;
        }

        $stmt_appointment->close();
    } else {
        echo "Error creating patient record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: Invalid or missing Day Picker value.";
}

$conn->close();
