<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('location: ../login.php');
    exit;
}

require_once('../car.class.php');

$id = (int)($_GET['id'] ?? 0);

if (isset($_POST['submit'])) {
    csrf_check();
    $c = new car();
    $c->name        = $_POST['name'];
    $c->brand       = $_POST['brand'];
    $c->price       = $_POST['price'];
    $c->description = $_POST['description'];
    $c->image       = $_POST['image'];

    $c->updateCar($id);
    header('location: index.php');
    exit;
}

$c   = new car();
$res = $c->getCarById($id);
$row = $res->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Car - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-bar">
    <div>Admin Panel - Edit a car</div>
    <div>
        <a href="index.php">Cars</a>
        <a href="rentals.php">Rentals</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="form-box">
    <h2>Edit Car #<?php echo $id; ?></h2>

    <form method="post" action="edit_car.php?id=<?php echo $id; ?>
        <?php echo csrf_field(); ?>">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo e($row['name']); ?>" required>

        <label>Brand</label>
        <input type="text" name="brand" value="<?php echo e($row['brand']); ?>" required>

        <label>Price per day ($)</label>
        <input type="number" step="0.01" name="price" value="<?php echo e($row['price']); ?>" required>

        <label>Description</label>
        <textarea name="description" rows="3"><?php echo e($row['description']); ?></textarea>

        <label>Image file name</label>
        <input type="text" name="image" value="<?php echo e($row['image']); ?>">

        <input type="submit" name="submit" value="Update Car" class="btn">
    </form>
</div>

</body>
</html>
