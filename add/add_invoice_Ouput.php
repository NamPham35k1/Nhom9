<?php
include '../connect.php';  // Kết nối cơ sở dữ liệu

// Truy vấn danh sách nhân viên
$sql = "SELECT id, name FROM user WHERE rule = 'nhân viên'";
$result = $conn->query($sql);

// Truy vấn danh sách sản phẩm
$sql = "SELECT id, name, price FROM inventory";
$result1 = $conn->query($sql);

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_supplier = $_POST['id_supplier'];
    $id_product = $_POST['id_product'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];

    // Lấy giá của sản phẩm
    $sql = "SELECT price FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_product);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    // Tính tổng tiền
    $total = $price * $quantity;

    // Kiểm tra nếu các trường không rỗng
    if (!empty($id_supplier) && !empty($id_product) && !empty($quantity) && !empty($date)) {
        // Chèn thông tin hóa đơn vào bảng bill
        $sql = "INSERT INTO bill_export(product_id, employee_id, quantity, total,date_bill) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiids',$id_product, $id_supplier, $quantity, $total, $date); 

        if ($stmt->execute()) {
            // Sau khi thành công, hiển thị thông báo
            echo "<div class='alert alert-success'>Hóa đơn đã được tạo thành công!</div>";
        } else {
            echo "<div class='alert alert-danger'>Lỗi khi tạo hóa đơn: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-warning'>Vui lòng điền đầy đủ thông tin!</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Hóa Đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function updateTotal() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const total = price * quantity;
            document.getElementById('total').value = total.toFixed(2);
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h3>Tạo Hóa Đơn</h3>

        <!-- Form tạo hóa đơn -->
        <form method="POST" action="add_invoice_Ouput.php">
            <!-- Chọn nhân viên -->
            <div class="form-group">
                <label for="supplier">Chọn nhân viên xuất</label>
                <select name="id_supplier" id="supplier" class="form-control" required>
                    <option value="">Chọn nhân viên</option>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Chọn sản phẩm -->
            <div class="form-group">
                <label for="product">Chọn sản phẩm</label>
                <select name="id_product" id="product" class="form-control" onchange="updatePrice()" required>
                    <option value="">Chọn sản phẩm</option>
                    <?php
                    while ($row = $result1->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "' data-price='" . $row['price'] . "'>" . $row['name'] . " - Giá: " . $row['price'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Hiển thị giá sản phẩm -->
            <div class="form-group">
                <label for="price">Giá sản phẩm</label>
                <input type="number" id="price" class="form-control" readonly>
            </div>

            <!-- Nhập số lượng -->
            <div class="form-group">
                <label for="quantity">Số lượng</label>
                <input type="number" name="quantity" id="quantity" class="form-control" oninput="updateTotal()" required>
            </div>

            <!-- Tổng tiền -->
            <div class="form-group">
                <label for="total">Tổng tiền</label>
                <input type="number" id="total" name="total" class="form-control" readonly>
            </div>

            <!-- Ngày tạo hóa đơn -->
            <div class="form-group">
                <label for="date">Ngày tạo hóa đơn</label>
                <input type="date" name="date" class="form-control" id="date" required>
            </div>

            <div class="d-flex mt-3">
                <button type="submit" class="btn btn-primary">Tạo hóa đơn</button>
                <a href="../index.php?page=invoiceOuput" class="btn btn-secondary" style="margin-left:20px;">Quay lại</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Cập nhật giá sản phẩm khi thay đổi
        function updatePrice() {
            const product = document.getElementById('product');
            const selectedOption = product.options[product.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('price').value = price;
            updateTotal();
        }
    </script>
</body>
</html>
