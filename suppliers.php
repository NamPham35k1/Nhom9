<?php

include 'connect.php';
$search = isset($_POST['search']) ? $_POST['search'] : '';

if (!empty($search)) {
    $sql = " SELECT id, donvi, address, sdt,diem FROM supplier WHERE donvi LIKE '%$search%'";
} else {
    
    $sql = "SELECT id, donvi, address, sdt,diem FROM supplier"; 
}


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        tbody tr:hover {
        background-color: #e7e5e4;
        cursor: pointer;
    }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3>Quản Lý Nhà Cung Cấp</h3>
        <a class="btn btn-success mt-2 mb-3" href="add/add_supplier.php">+ Thêm Dữ Liệu</a>
        <form method="POST" action="index.php?page=suppliers" class="mb-3">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Nhập tên đơn vị cung cấp..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">🔍 Tìm kiếm</button>
                    <a href="?page=suppliers" class="btn btn-secondary">✖️ Đặt lại</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Đơn vị cung cấp</th>
                        <th>Địa Chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Điểm</th>
                        <th>Chức Năng</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["donvi"] . "</td>";
                            echo "<td>" . $row["address"] . "</td>";
                            echo "<td>" . $row["sdt"] . "</td>";
                            echo "<td>" . $row["diem"] . "</td>";
                           
                            echo '<td>
                                      <a href="update/update_supplier.php?id=' . $row["id"] . '" class="btn btn-warning btn-sm">✏️</a>
                                  </td>';
                         
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Không có dữ liệu</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
