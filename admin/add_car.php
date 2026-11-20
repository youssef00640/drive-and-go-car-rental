<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('location: ../login.php');
    exit;
}

require_once('../car.class.php');

$msg = "";

if (isset($_POST['submit'])) {
    csrf_check();
    $c = new car();
    $c->name        = $_POST['name'];
    $c->brand       = $_POST['brand'];
    $c->price       = $_POST['price'];
    $c->description = $_POST['description'];
    $c->image       = $_POST['image'];

    $c->addCar();
    header('location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Car - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-bar">
    <div>Admin Panel - Add a new car</div>
    <div>
        <a href="index.php">Cars</a>
        <a href="rentals.php">Rentals</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="form-box">
    <h2>Add a Car</h2>

    <?php if ($msg != "") echo "<div class='message error'>$msg</div>"; ?>

    <form method="post" action="add_car.php">
        <?php echo csrf_field(); ?>
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Brand</label>
        <input type="text" name="brand" required>

        <label>Price per day ($)</label>
        <input type="number" step="0.01" name="price" required>

        <label>Description</label>
        <textarea name="description" rows="3"></textarea>

        <label>Image file name (place the file inside /images/)</label>
        <input type="text" name="image" placeholder="example.jpg">

        <input type="submit" name="submit" value="Add Car" class="btn">
    </form>
</div>

</body>
</html>
