<?php
require '../excel/vendor/autoload.php'; // Load PhpSpreadsheet
include '../db/connection.php'; // Include the database connection

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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

// Create Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Filtered Patient Records');

// Add headers
$sheet->setCellValue('A1', 'No.');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Age');
$sheet->setCellValue('D1', 'Gender');

// Merge cells for "Address" header
$sheet->setCellValue('E1', 'Address');
$sheet->mergeCells('E1:F1'); // Merge for Address
$sheet->setCellValue('E2', 'Barangay');
$sheet->setCellValue('F2', 'City/Municipality');

// Set other headers
$sheet->setCellValue('G1', 'Category');
$sheet->setCellValue('H1', 'Vaccine Type');
$sheet->setCellValue('I1', 'Schedule');

// Apply styles to the headers
$sheet->getStyle('A1:I1')->getFont()->setBold(true);
$sheet->getStyle('E1:F1')->getFont()->setBold(true);
$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:I2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Adjust column widths
foreach (range('A', 'I') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Apply filters to headers
$sheet->setAutoFilter('A1:I2');

// Populate data
$rowNumber = 3; // Start at row 3 after headers
$no = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $no++;
        $sheet->setCellValue('A' . $rowNumber, $no);
        $sheet->setCellValue('B' . $rowNumber, $row['firstname'] . ' ' . $row['midname'] . ' ' . $row['lastname']);
        $sheet->setCellValue('C' . $rowNumber, $row['age']);
        $sheet->setCellValue('D' . $rowNumber, ucfirst($row['gender']));
        $sheet->setCellValue('E' . $rowNumber, $row['barangay']);
        $sheet->setCellValue('F' . $rowNumber, $row['municipal']);
        $sheet->setCellValue('G' . $rowNumber, $row['category']);
        $sheet->setCellValue('H' . $rowNumber, $row['vaccine_type']);
        $sheet->setCellValue('I' . $rowNumber, $row['updated_at']);
        $rowNumber++;
    }
} else {
    // No records found
    $sheet->setCellValue('A3', 'No records found for the selected filters.');
    $sheet->mergeCells('A3:I3');
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Set output headers for Excel file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="filtered_patient_records.xlsx"');
header('Cache-Control: max-age=0');

// Save and output the file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

