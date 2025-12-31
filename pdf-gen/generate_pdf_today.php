<?php
require('../fpdf/fpdf.php'); // Include FPDF library
include '../db/connection.php'; // Include your database connection

// Extend FPDF class
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Set font
        $this->SetFont('Arial', 'B', 12);
        // Title
        $this->Cell(0, 10, 'Animal Bite Treatment Center', 0, 1, 'C');
        $this->Cell(0, 10, 'Patient Record - Today\'s Report', 0, 1, 'C');
        // Line break
        $this->Ln(5);

        // Table headers
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 10, 'NO', 1);                          // NO column
        $this->Cell(50, 10, 'NAME', 1);                        // NAME column
        $this->Cell(20, 10, 'AGE', 1);                         // AGE column
        $this->Cell(30, 10, 'GENDER', 1);                      // GENDER column
        $this->Cell(40, 10, 'BARANGAY', 1);                    // BARANGAY column
        $this->Cell(60, 10, 'CITY/MUNICIPALITY', 1);           // CITY/MUNICIPALITY column
        $this->Cell(30, 10, 'CATEGORY', 1);                    // CATEGORY column
        $this->Cell(35, 10, 'VACCINE', 1);                     // VACCINE column
        $this->Cell(40, 10, 'SCHEDULE', 1);                    // SCHEDULE column
        $this->Ln();
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Create instance of PDF class
$pdf = new PDF('L', 'mm', 'Legal'); // L for landscape, 'Legal' for paper size
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Fetch today's patient records
$sql = "SELECT 
            p.firstname, p.midname, p.lastname, p.age, p.gender, p.barangay, p.municipal, 
            f.category, f.vaccine_type, pq.created_at
        FROM patients p
        LEFT JOIN findings f ON p.patientid = f.patientid
        LEFT JOIN patient_que pq ON p.patientid = pq.patientid
        WHERE pq.status IN ('0', '1', '2' , '3') 
        AND (DATE(pq.created_at) = CURDATE() OR DATE(pq.updated_at) = CURDATE())";

$result = $conn->query($sql);

// Check if there are records
if ($result->num_rows > 0) {
    $no = 1;  // To display row numbers
    // Output data for each row
    while ($row = $result->fetch_assoc()) {
        $fullName = $row['firstname'] . ' ' . $row['midname'] . ' ' . $row['lastname'];

        $pdf->Cell(20, 10, $no++, 1);                               // NO
        $pdf->Cell(50, 10, $fullName, 1);                           // NAME
        $pdf->Cell(20, 10, $row['age'], 1);                         // AGE
        $pdf->Cell(30, 10, ucfirst($row['gender']), 1);             // GENDER
        $pdf->Cell(40, 10, $row['barangay'], 1);                    // BARANGAY
        $pdf->Cell(60, 10, $row['municipal'], 1);                   // CITY/MUNICIPALITY
        $pdf->Cell(30, 10, $row['category'], 1);                    // CATEGORY
        $pdf->Cell(35, 10, $row['vaccine_type'], 1);                // VACCINE
        $pdf->Cell(40, 10, date('m/d/Y', strtotime($row['created_at'])), 1); // SCHEDULE (from created_at)
        $pdf->Ln();
    }
} else {
    // If no records are found
    $pdf->Cell(0, 10, 'No records found for today', 1, 1, 'C');
}

// Output the PDF
$pdf->Output();
?>
