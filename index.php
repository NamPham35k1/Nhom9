<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý vật liệu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        #main-content {
            padding: 20px;
            background:#fdf4ff
        }
        
        li {
            border-radius: 10px;
        }
        li:hover {
            background-color: #fbc531;
        }
        .Sidebar {
            background-color: #192a56;
        }
        img {
            width: 100px;
            height: 100px;
            margin-left: 60px;
            margin-bottom: 40px;
            border-radius: 50px;
        }
        i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="Sidebar text-white p-3 vh-100" style="width: 300px;">
        <img src="assets/user.jpg" alt="User Image">
        <p style="margin-left: 65px;">73DCTT22</p>
        <hr width="180px" style="margin-left: 20px; margin-bottom: 50px;">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="?page=management" class="nav-link text-white"><i class="fa-solid fa-cart-shopping"></i> Quản Lý Khách Hàng</a>
            </li>
            <li class="nav-item mb-2">
                <a href="?page=user" class="nav-link text-white"><i class="fa-solid fa-circle-user"></i> Quản lý tài khoản</a>
            </li>
            <li class="nav-item mb-2">
                <a href="?page=manageEmployee" class="nav-link text-white"><i class="fa-solid fa-user"></i> Quản lý nhân viên</a>
            </li>
            <li class="nav-item mb-2">
                <a href="?page=expenses" class="nav-link text-white"><i class="fa-solid fa-warehouse"></i> Quản lý kho vật liệu</a>
            </li>
            <li class="nav-item mb-2">
                <a href="?page=suppliers" class="nav-link text-white"><i class="fa-solid fa-handshake"></i> Nhà cung cấp</a>
            </li>
            <li class="nav-item mb-2">
                <a href="?page=invoice" class="nav-link text-white"><i class="fa-solid fa-file-invoice"></i> Quản lý hóa đơn</a>
            </li>
            <li class="nav-item mb-2">
                <a href="?page=invoiceOuput" class="nav-link text-white"><i class="fa-solid fa-file-invoice"></i> Quản lý hóa đơn Xuất</a>
            </li>
            <li class="nav-item mb-2">
                <a href="?page=reports" class="nav-link text-white"><i class="fa-solid fa-money-bill"></i> Báo cáo chi tiêu</a>
            </li>
            <li class="nav-item mb-2">
                <a href="login.php" class="nav-link text-white" onclick="return confirmLogout();"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
            </li>
        </ul>

    </div>

    <div id="main-content" class="container-fluid">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'management';
        $allowed_pages = ['dashboard', 'management', 'user', 'manageEmployee', 'reports', 'suppliers', 'invoice', 'expenses','invoiceOuput'];

        if (in_array($page, $allowed_pages)) {
            $file_path = "$page.php";

            if (file_exists($file_path)) {
                include $file_path;
            } else {
                echo "<p>Trang không tồn tại hoặc chưa được triển khai.</p>";
            }
        } else {
            echo "<p>Trang không hợp lệ.</p>";
        }
        ?>
    </div>

    <script>
    function confirmLogout() {
        return confirm("Bạn có chắc chắn muốn thoát?");
    }
    </script>
</div>
</body>
</html>
