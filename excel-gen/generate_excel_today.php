<?php
require '../excel/vendor/autoload.php'; // Load PhpSpreadsheet
include '../db/connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Patient Report');

// Set the headers
$sheet->setCellValue('A1', 'NO');
$sheet->setCellValue('B1', 'NAME');
$sheet->setCellValue('C1', 'AGE');
$sheet->setCellValue('D1', 'SEX');

// Merge cells for the "ADDRESS" header
$sheet->setCellValue('E1', 'ADDRESS');
$sheet->mergeCells('E1:H1'); // Merging the cells from E1 to H1

// Sub-headers for the address columns
$sheet->setCellValue('E2', 'BARANGAY');
$sheet->setCellValue('F2', 'CITY/MUNICIPALITY');

// Set other headers
$sheet->setCellValue('I1', 'CATEGORY');
$sheet->setCellValue('J1', 'VACCINE');
$sheet->setCellValue('K1', 'SCHEDULE');

// Set alignment for the merged header
$sheet->getStyle('E1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Apply filters to the header range
$sheet->setAutoFilter('A1:K2');

// Adjust column widths to fit the content automatically
foreach (range('A', 'K') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Example SQL query to fetch necessary data for today
$query = "
    SELECT 
        p.patientid, 
        CONCAT(p.firstname, ' ', p.lastname) AS name, 
        p.age, 
        p.gender, 
        p.barangay, 
        p.municipal, 
        f.category, 
        f.vaccine_type, 
        a.appointment_date AS schedule
    FROM patients p
    JOIN findings f ON p.patientid = f.patientid
    JOIN appointment a ON p.patientid = a.patientid
    JOIN patient_que pq ON p.patientid = pq.patientid
    WHERE pq.status IN ('0', '1', '2','3') 
    AND (DATE(pq.created_at) = CURDATE() OR DATE(pq.updated_at) = CURDATE())
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $rowNum = 3; // Start adding data from row 3
    $count = 1;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNum, $count);
        $sheet->setCellValue('B' . $rowNum, $row['name']);
        $sheet->setCellValue('C' . $rowNum, $row['age']);
        $sheet->setCellValue('D' . $rowNum, ucfirst($row['gender']));
        $sheet->setCellValue('E' . $rowNum, $row['barangay']);
        $sheet->setCellValue('F' . $rowNum, $row['municipal']);
        $sheet->setCellValue('I' . $rowNum, $row['category']);
        $sheet->setCellValue('J' . $rowNum, $row['vaccine_type']);
        $sheet->setCellValue('K' . $rowNum, $row['schedule']);
        $rowNum++;
        $count++;
    }
}

$conn->close();

// Set the headers to download the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="patient_report_today.xlsx"');
header('Cache-Control: max-age=0');

// Write the file to the output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
