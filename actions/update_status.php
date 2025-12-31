<?php
include '../db/connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientIds = $_POST['patient_ids']; 
    $patientIdsArray = explode(',', $patientIds); 

    $response = ['status' => 'failed', 'message' => ''];

    try {
     
        $updateStatusQuery = "UPDATE patient_que SET status = 2 WHERE patient_id IN ($patientIds)";
        $conn->query($updateStatusQuery);

       

        $response['status'] = 'success';
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }

    
    echo json_encode($response);
}