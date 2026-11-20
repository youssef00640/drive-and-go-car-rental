<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('location: ../login.php');
    exit;
}

require_once('../car.class.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: index.php');
    exit;
}
csrf_check();

$c = new car();
$c->deleteCar($_POST['id'] ?? 0);

header('location: index.php');
?>
