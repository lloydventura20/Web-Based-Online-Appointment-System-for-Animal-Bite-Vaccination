<?php
include '../db/connection.php'; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    if (isset($_POST['patient_ids']) && !empty($_POST['patient_ids'])) {
        
  
        $patientIds = explode(',', $_POST['patient_ids']);
        $patientIds = array_map('intval', $patientIds);

        if (count($patientIds) > 0) {

          
            $conn->begin_transaction();

            try {
                
                $updatePreviousSql = "UPDATE patient_que SET status = '2' WHERE status = '1'";
                if (!$conn->query($updatePreviousSql)) {
                    throw new Exception('Failed to update previous patients');
                }

                
                $placeholders = implode(',', array_fill(0, count($patientIds), '?'));

               
                $sql = "UPDATE patient_que SET status = '1' WHERE patientid IN ($placeholders)";

                if ($stmt = $conn->prepare($sql)) {
                    
                    $stmt->bind_param(str_repeat('i', count($patientIds)), ...$patientIds);

                    if (!$stmt->execute()) {
                        throw new Exception('Failed to update new patient status');
                    }

                    
                    $conn->commit();
                    echo json_encode(['status' => 'success']);
                } else {
                    throw new Exception('Failed to prepare SQL statement');
                }
                
            } catch (Exception $e) {
               
                $conn->rollback();
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }

        } else {
            echo json_encode(['status' => 'error', 'message' => 'No valid patient IDs provided']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No patient IDs provided']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}


$conn->close();
