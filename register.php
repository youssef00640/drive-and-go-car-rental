<?php
session_start();
require_once('user.class.php');

$msg = "";

if (isset($_POST['submit'])) {
    csrf_check();

    $name     = $_POST['user_name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $phone    = $_POST['phone'];

    if (empty($name) || empty($email) || empty($password)) {
        $msg = "Please fill in all the required fields.";
    } elseif (strlen($password) < 8) {
        $msg = "Password must be at least 8 characters.";
    } else {
        $u = new user();
        $u->user_name = $name;
        $u->email     = $email;
        $u->password  = $password;
        $u->phone     = $phone;

        $row = $u->findEmail();
        $n   = $row->fetchColumn(0);

        if ($n == 0) {
            $u->addUser();
            header('location: login.php');
            exit;
        } else {
            $msg = "This email is already registered.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Drive&amp;Go</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">Drive&amp;Go</div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="cars.php">Cars</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<div class="form-box">
    <h2>Create your account</h2>

    <?php if ($msg != "") echo "<div class='message error'>$msg</div>"; ?>

    <form method="post" action="register.php">
        <?php echo csrf_field(); ?>
        <label>Full Name</label>
        <input type="text" name="user_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <input type="submit" name="submit" value="Register" class="btn">
        <div class="link">Already have an account? <a href="login.php">Login</a></div>
    </form>
</div>

<footer class="footer">
    &copy; <?php echo date('Y'); ?> Drive&amp;Go
</footer>

</body>
</html>
