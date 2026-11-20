<?php
session_start();
require_once('user.class.php');

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

$id  = $_SESSION['user_id'];
$msg = "";

if (isset($_POST['submit'])) {
    csrf_check();
    $u = new user();
    $u->user_name = $_POST['user_name'];
    $u->phone     = $_POST['phone'];
    $u->password  = $_POST['password'];

    $u->updateUser($id);

    $_SESSION['user_name'] = $_POST['user_name'];
    $msg = "Profile updated successfully.";
}

$u   = new user();
$res = $u->getUserById($id);
$row = $res->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Drive&amp;Go</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">Drive&amp;Go</div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="cars.php">Cars</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="form-box">
    <h2>My Profile</h2>

    <?php if ($msg != "") echo "<div class='message success'>$msg</div>"; ?>

    <form method="post" action="profile.php">
        <?php echo csrf_field(); ?>
        <label>Full Name</label>
        <input type="text" name="user_name" value="<?php echo e($row['user_name']); ?>" required>

        <label>Email (cannot be changed)</label>
        <input type="email" value="<?php echo e($row['email']); ?>" readonly>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo e($row['phone']); ?>">

        <label>New Password (leave blank to keep the current one)</label>
        <input type="password" name="password" autocomplete="new-password">

        <input type="submit" name="submit" value="Update Profile" class="btn">
    </form>
</div>

<footer class="footer">
    &copy; <?php echo date('Y'); ?> Drive&amp;Go
</footer>

</body>
</html>
