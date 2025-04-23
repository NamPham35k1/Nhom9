<?php
// Kết nối CSDL
include 'connect.php';

// Truy vấn dữ liệu hóa đơn
$sql = "SELECT *FROM bill_export";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Hóa Đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        tbody tr:hover {
        background-color: #e7e5e4;
        cursor: pointer;
     }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Quản Lý Hóa Đơn Xuất</h1>
        
        <div class="text-end mb-3">
            <a href="add/add_invoice_Ouput.php" class="btn btn-primary">Tạo Hóa Đơn</a>
        </div>

        <table class="table table-bordered text-center">
            <thead class="table-success">
                <tr>
                    <th>STT</th>
                    <th>Mã hóa đơn</th>
                    <th>Mã Sản Phẩm</th>
                    <th>Mã Nhân Viên Xuất</th>
                    <th>Thời gian</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $stt = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$stt}</td>";
                        echo "<td>{$row['id_bill']}</td>";
                        echo "<td>{$row['product_id']}</td>";
                        echo "<td>{$row['employee_id']}</td>";
                        echo "<td>{$row['date_bill']}</td>";
                        echo "<td>{$row['quantity']}</td>";
                        echo "<td>" . number_format($row['total'], 0, ',', '.') . "</td>";
                        echo '<td><a href="detail_invoiceOuput.php?id=' . $row['id_bill'] . '" class="btn btn-view">Xem hóa đơn</a></td>';
                        echo "</tr>";
                        $stt++;
                    }
                } else {
                    echo "<tr><td colspan='5'>Không có hóa đơn nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
