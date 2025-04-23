<?php
include 'connect.php';

$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$bills = []; 
$totalAmount = 0; 

if ($startDate && $endDate) {
    $sql = "SELECT id_bill,  total,date_bill FROM bill_export WHERE DATE(date_bill) BETWEEN ? AND ? ORDER BY date_bill ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bills[] = $row;
            $totalAmount += $row['total']; 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Thống Kê Hóa Đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        tbody tr:hover {
        background-color: #e7e5e4;
        cursor: pointer;
     }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3>Báo Cáo Thống Kê Hóa Đơn</h3>
        <form method="POST" action="">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="start_date" class="form-label">Từ ngày</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required>
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">Đến ngày</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Lọc</button>
            <button type="submit" formaction="export_excel.php" class="btn btn-success">Xuất Excel</button>
        </div>
    </div>
        </form>


        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Ngày Hóa Đơn</th>
                    <th>Tổng Tiền (VND)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bills)): ?>
                    <?php $stt = 1; ?>
                    <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($bill['date_bill'])); ?></td>
                            <td><?php echo number_format($bill['total'], 0, ',', '.'); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Không có dữ liệu trong khoảng thời gian đã chọn.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (!empty($bills)): ?>
            <div class="alert alert-info">
                <strong>Tổng Chi Tiêu: </strong><?php echo number_format($totalAmount, 0, ',', '.'); ?> VND
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
