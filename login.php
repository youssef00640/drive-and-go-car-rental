<?php
session_start();
require_once('user.class.php');

$msg = "";

if (isset($_POST['submit'])) {
    csrf_check();

    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $msg = "Please enter your email and password.";
    } else {
        $u = new user();
        $u->email    = $email;
        $u->password = $password;

        $row = $u->login();

        if ($row) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['role']      = $row['role'];

            if ($row['role'] == 'admin') {
                header('location: admin/index.php');
                exit;
            } else {
                header('location: index.php');
                exit;
            }
        } else {
            $msg = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Drive&amp;Go</title>
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
    <h2>Welcome back</h2>

    <?php if ($msg != "") echo "<div class='message error'>$msg</div>"; ?>

    <form method="post" action="login.php">
        <?php echo csrf_field(); ?>
        <label>Login</label>
        <input type="text" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <input type="submit" name="submit" value="Login" class="btn">
        <div class="link">No account yet? <a href="register.php">Register</a></div>
    </form>
</div>

<footer class="footer">
    &copy; <?php echo date('Y'); ?> Drive&amp;Go
</footer>

</body>
</html>
