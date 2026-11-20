<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('location: ../login.php');
    exit;
}

require_once('../car.class.php');

$c   = new car();
$res = $c->listCars();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Drive&amp;Go</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-bar">
    <div>Admin Panel - Welcome <?php echo e($_SESSION['user_name']); ?></div>
    <div>
        <a href="index.php">Cars</a>
        <a href="add_car.php">+ Add Car</a>
        <a href="rentals.php">Rentals</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="table-wrap">
    <h2>Cars Management</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Price/Day</th>
            <th>Actions</th>
        </tr>

        <?php
        foreach ($res as $row) {
            echo "<tr>";
            echo "<td>".e($row['car_id'])."</td>";
            echo "<td><img src='../images/".e($row['image'])."' width='80' height='50' style='object-fit:cover; border-radius:4px;'></td>";
            echo "<td>".e($row['name'])."</td>";
            echo "<td>".e($row['brand'])."</td>";
            echo "<td>$".e($row['price'])."</td>";
            echo "<td>
                    <a class='action-link' href='edit_car.php?id=".e($row['car_id'])."'>Edit</a>
                    <form method='post' action='delete_car.php' style='display:inline'
                          onsubmit=\"return confirm('Delete this car?');\">
                        ".csrf_field()."
                        <input type='hidden' name='id' value='".e($row['car_id'])."'>
                        <button type='submit' class='action-link delete'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
