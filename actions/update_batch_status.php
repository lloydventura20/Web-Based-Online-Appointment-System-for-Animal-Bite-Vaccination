<?php
include '../db/connection.php'; // Adjust the path to your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['batchNumber'])) {
        $batchNumber = intval($_POST['batchNumber']); // Sanitize input

        // Step 1: Update all patients with `status = 2` to `status = 3`
        $updateStatus2To3 = "UPDATE patient_que 
                             SET status = '3' 
                             WHERE status = '2'";
        $updateResult = mysqli_query($conn, $updateStatus2To3);

        if (!$updateResult) {
            echo 'Error updating status 2 to 3: ' . mysqli_error($conn);
            exit;
        }

        // Step 2: Fetch a new batch of patients with `status = 1` and update their status to `2`
        $offset = ($batchNumber - 1) * 10; // Calculate the offset for the batch
        $queryFetch = "
            SELECT pq.queid, pq.patientid, f.findingid, f.dose 
            FROM patient_que pq
            JOIN findings f ON pq.patientid = f.patientid
            WHERE pq.status = '1'
            LIMIT $offset, 10";

        $resultFetch = mysqli_query($conn, $queryFetch);

        if (!$resultFetch) {
            echo 'Error fetching new batch: ' . mysqli_error($conn);
            exit;
        }

        if (mysqli_num_rows($resultFetch) > 0) {
            while ($row = mysqli_fetch_assoc($resultFetch)) {
                $queid = $row['queid'];
                $patientid = $row['patientid'];
                $findingsid = $row['findingid'];
                $dose = $row['dose'];

                // Update status to 2
                $updateStatus1To2 = "UPDATE patient_que 
                                     SET status = '2' 
                                     WHERE queid = $queid";
                $updateResult = mysqli_query($conn, $updateStatus1To2);

                if (!$updateResult) {
                    echo 'Error updating status to 2 for queid ' . $queid . ': ' . mysqli_error($conn);
                    exit;
                }

                // Update `findings` table based on dose type
                $currentDate = date('Y-m-d H:i:s'); // Current date and time
                if ($dose === '1st Dose') {
                    $updateFindings = "UPDATE findings 
                                       SET d1 = '$currentDate' 
                                       WHERE findingid = $findingsid";
                } elseif ($dose === '2nd Dose') {
                    $updateFindings = "UPDATE findings 
                                       SET d3 = '$currentDate' 
                                       WHERE findingid = $findingsid";
                } elseif ($dose === '3rd Dose') {
                    $updateFindings = "UPDATE findings 
                                       SET d7 = '$currentDate' 
                                       WHERE findingid = $findingsid";
                } else {
                    echo 'Invalid dose type for patientid ' . $patientid;
                    exit;
                }

                $updateFindingsResult = mysqli_query($conn, $updateFindings);
                if (!$updateFindingsResult) {
                    echo 'Error updating findings for findingid ' . $findingsid . ': ' . mysqli_error($conn);
                    exit;
                }

                // Insert into `report` table with the correct `dose`
                $insertReport = "INSERT INTO report (patientid, findingsid, dose, created_at)
                                 VALUES ($patientid, $findingsid, '$dose', NOW())";

                $insertResult = mysqli_query($conn, $insertReport);

                if (!$insertResult) {
                    echo 'Error inserting report for patientid ' . $patientid . ': ' . mysqli_error($conn);
                    exit;
                }
            }
            echo 'success';
        } else {
            echo 'error'; // No patients found in this batch
        }
    } else {
        echo 'Invalid request: batchNumber is missing.';
    }
} else {
    echo 'Invalid request method.';
}
