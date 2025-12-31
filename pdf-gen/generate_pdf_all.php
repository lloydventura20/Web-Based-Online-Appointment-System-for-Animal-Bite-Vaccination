<?php
require('../fpdf/fpdf.php'); // Include the FPDF library
include '../db/connection.php'; // Include the database connection

// Fetch filter values
$year = isset($_GET['year']) ? $_GET['year'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Base SQL query
$sql = "SELECT p.firstname, p.midname, p.lastname, p.age, p.gender, p.barangay, p.municipal, f.category, f.vaccine_type, pq.updated_at
        FROM patients p
        LEFT JOIN findings f ON p.patientid = f.patientid
        LEFT JOIN patient_que pq ON p.patientid = pq.patientid
        WHERE pq.status IN ('0', '1', '2','3')";

// Add filters
if (!empty($year)) $sql .= " AND YEAR(pq.updated_at) = '$year'";
if (!empty($month)) $sql .= " AND MONTH(pq.updated_at) = '$month'";
if (!empty($start_date) && !empty($end_date)) {
    $sql .= " AND DATE(pq.updated_at) BETWEEN '$start_date' AND '$end_date'";
} elseif (!empty($start_date)) {
    $sql .= " AND DATE(pq.updated_at) >= '$start_date'";
} elseif (!empty($end_date)) {
    $sql .= " AND DATE(pq.updated_at) <= '$end_date'";
}

$result = $conn->query($sql);

// Initialize FPDF in landscape mode
$pdf = new FPDF('L', 'mm', 'A4'); // 'L' stands for Landscape
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add title
$pdf->Cell(275, 10, 'Animal Bite Treatment Center', 0, 1, 'C'); // Width adjusted for landscape
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(275, 10, 'Patient Record Table', 0, 1, 'C');
$pdf->Ln(5); // Add some space

// Add table headers
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'No.', 1, 0, 'C');
$pdf->Cell(50, 10, 'Name', 1, 0, 'C');
$pdf->Cell(15, 10, 'Age', 1, 0, 'C');
$pdf->Cell(20, 10, 'Gender', 1, 0, 'C');
$pdf->Cell(40, 10, 'Barangay', 1, 0, 'C');
$pdf->Cell(40, 10, 'City/Municipality', 1, 0, 'C');
$pdf->Cell(30, 10, 'Category', 1, 0, 'C');
$pdf->Cell(30, 10, 'Vaccine', 1, 0, 'C');
$pdf->Cell(40, 10, 'Schedule', 1, 1, 'C'); // End the row

// Populate data
$pdf->SetFont('Arial', '', 10);
$no = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $no++;
        $name = $row['firstname'] . ' ' . $row['midname'] . ' ' . $row['lastname'];
        $barangay = $row['barangay'];
        $municipal = $row['municipal'];
        $category = $row['category'];
        $vaccine_type = $row['vaccine_type'];
        $schedule = $row['updated_at'];

        $pdf->Cell(10, 10, $no, 1, 0, 'C');
        $pdf->Cell(50, 10, $name, 1, 0, 'C');
        $pdf->Cell(15, 10, $row['age'], 1, 0, 'C');
        $pdf->Cell(20, 10, ucfirst($row['gender']), 1, 0, 'C');
        $pdf->Cell(40, 10, $barangay, 1, 0, 'C');
        $pdf->Cell(40, 10, $municipal, 1, 0, 'C');
        $pdf->Cell(30, 10, $category, 1, 0, 'C');
        $pdf->Cell(30, 10, $vaccine_type, 1, 0, 'C');
        $pdf->Cell(40, 10, $schedule, 1, 1, 'C');
    }
} else {
    $pdf->Cell(275, 10, 'No records found for the selected filters.', 1, 1, 'C');
}

// Output the PDF
$pdf->Output('D', 'patient_record_table_landscape.pdf');
exit;

