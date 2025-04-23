<?php
include 'connect.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT id, name, type, price, discount, image, quantity, status 
        FROM inventory 
        WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["type"] . "</td>";
        echo "<td>" . $row["price"] . "</td>";
        echo "<td>" . $row["discount"] . "</td>";
        if (!empty($row["image"])) {
            echo "<td><img src='../assets/" . $row["image"] . "' alt='Image' width='100' height='100'></td>";
        } else {
            echo "<td>No image</td>";
        }
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";
        echo '<td>
                <a href="update/update_expenses.php?id=' . $row["id"] . '" class="btn btn-warning btn-sm">✏️</a>
                <a href="delete/dl_expenses.php?id=' . $row["id"] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa không?\');">❌</a>
              </td>';
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>Không có dữ liệu phù hợp</td></tr>";
}

$conn->close();
?>
