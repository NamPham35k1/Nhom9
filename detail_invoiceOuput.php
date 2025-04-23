<?php
include 'connect.php';  // Kết nối cơ sở dữ liệu
// Kiểm tra nếu có id_bill trong URL
if (isset($_GET['id'])) {
    $id_bill = $_GET['id'];

    // Truy vấn thông tin từ bảng bill_export dựa trên id_bill
    $sql = "SELECT bill_export.product_id, bill_export.employee_id, bill_export.quantity, bill_export.total, bill_export.date_bill, inventory.name AS product_name, user.name AS employee_name FROM bill_export
            JOIN inventory ON bill_export.product_id = inventory.id
            JOIN user ON bill_export.employee_id = user.id
            WHERE bill_export.id_bill = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_bill);
    $stmt->execute();
    $result = $stmt->get_result();
    // Lấy kết quả từ truy vấn
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_name = $row['product_name'];
        $employee_name = $row['employee_name'];
        $quantity = $row['quantity'];
        $total = $row['total'];
        $date_bill = $row['date_bill'];
    } else {
        echo "<div class='alert alert-danger'>Không tìm thấy hóa đơn với ID này.</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-warning'>Không có ID hóa đơn trong URL.</div>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Hóa Đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Chi Tiết Hóa Đơn</h3>

        <!-- Hiển thị thông tin hóa đơn -->
        <?php if (isset($product_name)): ?>
            <table class="table">
                <tr>
                    <th>Tên sản phẩm</th>
                    <td><?= $product_name ?></td>
                </tr>
                <tr>
                    <th>Tên nhân viên</th>
                    <td><?= $employee_name ?></td>
                </tr>
                <tr>
                    <th>Số lượng</th>
                    <td><?= $quantity ?></td>
                </tr>
                <tr>
                    <th>Tổng tiền</th>
                    <td><?= number_format($total, 2) ?> VND</td>
                </tr>
                <tr>
                    <th>Ngày tạo hóa đơn</th>
                    <td><?= date('d/m/Y', strtotime($date_bill)) ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <div class="d-flex mt-3">
            <a href="index.php?page=invoiceOuput" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
