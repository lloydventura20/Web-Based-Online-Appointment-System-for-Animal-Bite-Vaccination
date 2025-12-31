<?php
require('../fpdf/fpdf.php'); // Include the FPDF library
include '../db/connection.php'; // Include your database connection

class PDF extends FPDF
{
    function Header()
    {
        // Set font for header
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Patient Form', 0, 1, 'C');
    }

    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function FormContent($data)
    {
        // Set font
        $this->SetFont('Arial', '', 12);

        // Registration Info
        $this->Cell(50, 10, 'Registration No.: ' . $data['patientid'], 0, 0);
        $this->Cell(80, 10, '       Date Registered: ' . date('Y-m-d'), 0, 1); // Current date for demo

        $this->Cell(100, 10, 'Name: ' . $data['lastname'] . ', ' . $data['firstname'] . ' ' . $data['midname'] . ' ' . $data['sufix'], 0, 0);
        $this->Cell(40, 10, 'Age: ' . $data['age'], 0, 1);

        $this->Cell(100, 10, 'Address: ' . $data['barangay'] . ', ' . $data['municipal'] . ', ' . $data['province'], 0, 0);
        $this->Cell(40, 10, 'Sex: ' . ucfirst($data['gender']), 0, 1);

        $this->Ln(10); // Line break

        // History of Exposure
        $this->Cell(0, 10, 'History of Exposure:', 0, 1);
        $this->Cell(100, 10, 'Date of Exposure: ' . $data['dob'], 0, 1); // Example date, update with actual data
        $this->Cell(100, 10, 'Place of Exposure: ' . $data['pob'], 0, 1);
        $this->Cell(100, 10, 'Type of Exposure: ' . $data['wound_type'], 0, 1);
        $this->Cell(100, 10, 'Source of Exposure: ' . $data['animal_type'], 0, 1);

        $this->Ln(10); // Line break

        // Category of Exposure
        $this->Cell(0, 10, 'Category of Exposure: ' . $data['category'], 0, 1);

        // Post Exposure Prophylaxis
        $this->Cell(0, 10, 'Post Exposure Prophylaxis:', 0, 1);

        // Sub-parts
        $this->Cell(10, 10, 'A. ', 0, 0);
        $this->Cell(0, 10, 'Washing of Bite Wound: ' . $data['wound_wash'], 0, 1);

        $this->Cell(10, 10, 'B. ', 0, 0);
        $this->Cell(0, 10, 'RIG: ' . $data['erig'], 0, 1);

        $this->Cell(10, 10, 'C. ', 0, 0);
        $this->Cell(0, 10, 'Anti-Rabies Vaccine:', 0, 1);

        // Anti-Rabies Vaccine details
        $this->Cell(20, 10, '1. ', 0, 0);
        $this->Cell(0, 10, 'Generic Name: ' . $data['vaccine_type'] . ' Brand Name: _______', 0, 1);

        $this->Cell(20, 10, '2. ', 0, 0);
        $this->Cell(0, 10, 'Route: ____________________________', 0, 1);

        $this->Cell(20, 10, '- ', 0, 0);
        $this->Cell(0, 10, 'D0: '. $data['d1'] . '                       D14(IM): __________', 0, 1);

        $this->Cell(20, 10, '- ', 0, 0);
        $this->Cell(0, 10, 'D3: '. $data['d3'] .'                       D28/30: ' . $data['d2030'], 0, 1);

        $this->Cell(20, 10, '- ', 0, 0);
        $this->Cell(0, 10, 'D7: ' . $data['d7'] .' (If dog is not alive after 14 Days of observation)', 0, 1);

        $this->Ln(10); // Line break

        // Status of Animal
        $this->Cell(0, 10, 'Status of animal 14 days after exposure: _______________________', 0, 1);

        $this->Cell(0, 10, 'Remarks: ____________________________________________________', 0, 1);
    }
}

// Create instance of the PDF class
$pdf = new PDF();
$pdf->AddPage();

// Get the POST data from the AJAX request
$patientId = $_POST['patientId'];
$findingId = $_POST['findingId'];
$queId = $_POST['queId'];

// Fetch data using JOINs
$sql = "
    SELECT 
        p.*, 
        f.*, 
        q.status as queue_status
    FROM patients p
    JOIN findings f ON p.patientid = f.patientid
    JOIN patient_que q ON p.patientid = q.patientid AND f.findingid = q.findingsid
    WHERE p.patientid = ? AND f.findingid = ? AND q.queid = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $patientId, $findingId, $queId);
$stmt->execute();
$result = $stmt->get_result();

// Check if a record was found
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("No data found.");
}

// Close the database connection
$stmt->close();
$conn->close();

// Add form content to PDF
$pdf->FormContent($data);
$pdf->Output();

