<?php

include 'connect.php';
$search = isset($_POST['search']) ? $_POST['search'] : '';

if (!empty($search)) {
    $sql = "SELECT id, name, email, sdt, address FROM user WHERE name LIKE '%$search%'";
} else {
    
    $sql = "SELECT id, name, email, sdt, address FROM user";
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
        <h3>Quản Lý Nhân Viên</h3>

    
        <form method="POST" action="index.php?page=manageEmployee" class="mb-3">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Nhập tên nhân viên..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">🔍 Tìm kiếm</button>
                    <a href="?page=manageEmployee" class="btn btn-secondary">✖️ Đặt lại</a>
                </div>
            </div>
        </form>

   
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Tùy chỉnh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["sdt"] . "</td>";
                            echo "<td>" . $row["address"] . "</td>";
                            echo '<td>
                                    <a href="update/update_employee.php?id=' . $row["id"] . '" class="btn btn-warning btn-sm">✏️</a>
                                  </td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Không tìm thấy kết quả phù hợp.</td></tr>";
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
