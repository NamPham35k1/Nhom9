<?php
include 'connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT b.id_invoice, s.donvi, b.total, b.date 
            FROM bill b
            JOIN supplier s ON b.id_supplier = s.id
            WHERE b.id_invoice = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "Không tìm thấy hóa đơn!";
            exit;
        }

        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị câu lệnh SQL: " . $conn->error;
        exit;
    }
} else {
    echo "ID hóa đơn không hợp lệ!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem Hóa Đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Chi Tiết Hóa Đơn</h3>

        <table class="table table-bordered">
            <tr>
                <th>Mã Hóa Đơn</th>
                <td><?php echo htmlspecialchars($row['id_invoice']); ?></td>
            </tr>
            <tr>
                <th>Đơn vị cung cấp</th>
                <td><?php echo htmlspecialchars($row['donvi']); ?></td>
            </tr>
            <tr>
                <th>Tổng tiền</th>
                <td><?php echo number_format($row['total'], 0, ',', '.'); ?> VNĐ</td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
            </tr>
        </table>

        <a href="index.php?page=invoice" class="btn btn-secondary">Quay lại</a>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
