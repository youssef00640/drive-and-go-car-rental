<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('location: ../login.php');
    exit;
}

require_once('../rental.class.php');

$r   = new rental();
$res = $r->listRentals();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rentals - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-bar">
    <div>Admin Panel - Rentals</div>
    <div>
        <a href="index.php">Cars</a>
        <a href="add_car.php">+ Add Car</a>
        <a href="rentals.php">Rentals</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="table-wrap">
    <h2>All Rentals</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Car</th>
            <th>Start</th>
            <th>End</th>
            <th>Total</th>
        </tr>

        <?php
        foreach ($res as $row) {
            echo "<tr>";
            echo "<td>".e($row['rental_id'])."</td>";
            echo "<td>".e($row['user_name'])."</td>";
            echo "<td>".e($row['brand'])." ".e($row['name'])."</td>";
            echo "<td>".e($row['start_date'])."</td>";
            echo "<td>".e($row['end_date'])."</td>";
            echo "<td>$".e($row['total_price'])."</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
