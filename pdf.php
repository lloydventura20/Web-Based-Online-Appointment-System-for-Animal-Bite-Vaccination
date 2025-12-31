<?php
require('fpdf/fpdf.php'); // Include the FPDF library
include 'db/connection.php';

class PDF extends FPDF
{
    function Header()
    {
        // Set font for header
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Patient Exposure Form', 0, 1, 'C');
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

    function FormContent()
    {
        // Set font
        $this->SetFont('Arial', '', 12);

        // Registration Info
        $this->Cell(50, 10, 'Registration No.: ______', 0, 0);
        $this->Cell(80, 10, 'Date Registered: ___________', 0, 1);

        $this->Cell(100, 10, 'Name: ______________________________', 0, 0);
        $this->Cell(40, 10, 'Age: ___________', 0, 1);

        $this->Cell(100, 10, 'Address: ______________________________', 0, 0);
        $this->Cell(40, 10, 'Sex: ___________', 0, 1);

        $this->Ln(10); // Line break

        // History of Exposure
        $this->Cell(0, 10, 'History of Exposure:', 0, 1);
        $this->Cell(100, 10, 'Date of Exposure: ____________________', 0, 1);
        $this->Cell(100, 10, 'Place of Exposure: ____________________', 0, 1);
        $this->Cell(100, 10, 'Type of Exposure: _____________________', 0, 1);
        $this->Cell(100, 10, 'Source of Exposure: ___________________', 0, 1);

        $this->Ln(10); // Line break

        // Category of Exposure
        $this->Cell(0, 10, 'Category of Exposure: ________________________________', 0, 1);

        // Post Exposure Prophylaxis
        $this->Cell(0, 10, 'Post Exposure Prophylaxis:', 0, 1);

        // Sub-parts
        $this->Cell(10, 10, 'A. ', 0, 0);
        $this->Cell(0, 10, 'Washing of Bite Wound: ___________________________', 0, 1);

        $this->Cell(10, 10, 'B. ', 0, 0);
        $this->Cell(0, 10, 'RIG: ____________________________________________', 0, 1);

        $this->Cell(10, 10, 'C. ', 0, 0);
        $this->Cell(0, 10, 'Anti-Rabies Vaccine:', 0, 1);

        // Anti-Rabies Vaccine details
        $this->Cell(20, 10, '1. ', 0, 0);
        $this->Cell(0, 10, 'Generic Name: _______ Brand Name: _______', 0, 1);

        $this->Cell(20, 10, '2. ', 0, 0);
        $this->Cell(0, 10, 'Route: ____________________________', 0, 1);

        $this->Cell(20, 10, '3. ', 0, 0);
        $this->Cell(0, 10, 'D0: __________ D14(IM): __________', 0, 1);

        $this->Cell(20, 10, '4. ', 0, 0);
        $this->Cell(0, 10, 'D3: __________ D28/30: __________', 0, 1);

        $this->Cell(20, 10, '5. ', 0, 0);
        $this->Cell(0, 10, 'D7: __________ (If dog is not alive after 14 Days of observation)', 0, 1);

        $this->Ln(10); // Line break

        // Status of Animal
        $this->Cell(0, 10, 'Status of animal 14 days after exposure: _______________________', 0, 1);

        $this->Cell(0, 10, 'Remarks: ____________________________________________________', 0, 1);
    }
}

// Create instance of the PDF class
$pdf = new PDF();
$pdf->AddPage();
$pdf->FormContent();
$pdf->Output();

