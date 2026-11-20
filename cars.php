<?php
session_start();
require_once('car.class.php');

$cars = new car();
$res  = $cars->listCars();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Cars - Drive&amp;Go</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">Drive&amp;Go</div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="cars.php">Cars</a></li>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php } else { ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php } ?>
    </ul>
</nav>

<h2 class="section-title">Available Cars</h2>

<div class="cars-grid">
<?php
foreach ($res as $row) {
    echo "<div class='car-card'>";
    echo "  <img src='images/".e($row['image'])."' alt='".e($row['name'])."'>";
    echo "  <div class='car-info'>";
    echo "    <h3>".e($row['name'])."</h3>";
    echo "    <div class='brand'>Brand: ".e($row['brand'])."</div>";
    echo "    <div class='price'>$".e($row['price'])." / day</div>";
    echo "    <p class='desc'>".e($row['description'])."</p>";
    echo "    <a href='rent.php?id=".e($row['car_id'])."' class='btn'>Rent Now</a>";
    echo "  </div>";
    echo "</div>";
}
?>
</div>

<footer class="footer">
    &copy; <?php echo date('Y'); ?> Drive&amp;Go - University Student Project
</footer>

</body>
</html>
