<?php 
session_start();
include '../db/connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $cpnumber = $_POST['cpnumber'] ?? '';

    if (!empty($email) && !empty($cpnumber)) {
        
        // Query to join patients and appointment tables
        $stmt = $conn->prepare("
            SELECT p.*, a.appointmentid, a.appointment_day, a.appointment_date, a.status AS appointment_status
            FROM patients p
            LEFT JOIN appointment a ON p.patientid = a.patientid
            WHERE p.email = ? AND p.cpnumber = ?
        ");
        $stmt->bind_param("ss", $email, $cpnumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch all rows (as there might be multiple appointments)
            $user_data = [];
            while ($user = $result->fetch_assoc()) {
                $user_data[] = $user;
            }

            // Save email in session (optional)
            $_SESSION['user_email'] = $user_data[0]['email'];

            // Return the data as JSON
            echo json_encode([
                "status" => "Success",
                "message" => "Login successful.",
                "user" => $user_data // Return user and appointment details
            ]);
        } else {
            // If no matching record found
            echo json_encode([
                "status" => "Error",
                "message" => "Incorrect email or mobile number."
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "status" => "Error",
            "message" => "All fields are required."
        ]);
    }
}

$conn->close();
