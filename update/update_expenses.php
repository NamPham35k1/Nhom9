<?php

include '../connect.php';


$id = isset($_GET['id']) ? $_GET['id'] : '';


$sql = "SELECT id, name, type, price, discount, image, quantity, status FROM inventory WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Sản phẩm không tồn tại!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];


    $updateSql = "UPDATE inventory SET name = ?, type = ?, price = ?, discount = ?, quantity = ?, status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssdiisi", $name, $type, $price, $discount, $quantity, $status, $id);
    
    if ($updateStmt->execute()) {
        echo "Cập nhật sản phẩm thành công!";
        header("Location: ../index.php?page=expenses");
        exit();
    } else {
        echo "Lỗi khi cập nhật sản phẩm!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Sửa Sản Phẩm</h3>

     
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Tên Sản Phẩm</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Loại Sản Phẩm</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($product['type']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá Tiền</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="discount" class="form-label">Giảm Giá (%)</label>
                <input type="number" class="form-control" id="discount" name="discount" value="<?php echo htmlspecialchars($product['discount']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Số Lượng</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Tình Trạng</label>
                <input type="text" class="form-control" id="status" name="status" value="<?php echo htmlspecialchars($product['status']); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Cập Nhật</button>
        </form>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php

$conn->close();
?>
