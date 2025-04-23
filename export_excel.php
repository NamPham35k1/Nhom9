<?php
require 'vendor/autoload.php'; // Nạp thư viện PhpSpreadsheet
include 'connect.php'; // Kết nối cơ sở dữ liệu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

if ($startDate && $endDate) {
    $sql = "SELECT id_bill, total,date_bill FROM bill_export WHERE DATE(date_bill) BETWEEN ? AND ? ORDER BY date_bill ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tạo đối tượng Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Báo Cáo Hóa Đơn");

    // Thêm tiêu đề cột
    $sheet->setCellValue('A1', 'STT');
    $sheet->setCellValue('B1', 'Ngày Hóa Đơn');
    $sheet->setCellValue('C1', 'Tổng Tiền (VND)');

    // Thêm dữ liệu
    $rowIndex = 2;
    $stt = 1;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue("A{$rowIndex}", $stt++);
        $sheet->setCellValue("B{$rowIndex}", date('d-m-Y', strtotime($row['date_bill'])));
        $sheet->setCellValue("C{$rowIndex}", $row['total']);
        $rowIndex++;
    }

    // Xuất file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="bao_cao_hoa_don.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}
?>
