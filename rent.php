<?php
session_start();
require_once('car.class.php');
require_once('rental.class.php');

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('location: cars.php');
    exit;
}

$car_id = (int)$_GET['id'];

$c   = new car();
$res = $c->getCarById($car_id);
$car = $res->fetch(PDO::FETCH_ASSOC);
if (!$car) {
    header('location: cars.php');
    exit;
}

$msg = "";

if (isset($_POST['submit'])) {
    csrf_check();
    $start = $_POST['start_date'];
    $end   = $_POST['end_date'];

    $validDate = fn($d) => (bool)preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && strtotime($d) !== false;
    $days = ($validDate($start) && $validDate($end))
        ? (strtotime($end) - strtotime($start)) / (60 * 60 * 24) : 0;

    if (!$validDate($start) || !$validDate($end) || $start < date('Y-m-d')) {
        $msg = "Please choose valid dates from today onwards.";
    } elseif ($days <= 0) {
        $msg = "End date must be after the start date.";
    } else {
        $r = new rental();
        $conflict = $r->countConflict($car_id, $start, $end);

        if ($conflict > 0) {
            $msg = "Sorry, this car is already reserved during those dates. Please choose other dates.";
        } else {
            $total = $days * $car['price'];

            $r->user_id     = $_SESSION['user_id'];
            $r->car_id      = $car_id;
            $r->start_date  = $start;
            $r->end_date    = $end;
            $r->total_price = $total;
            $r->addRental();

            $msg = "Rental confirmed! Total to pay: $" . $total;
        }
    }
}

$r   = new rental();
$booked = $r->getRentalsByCar($car_id);
$bookedList = $booked->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent <?php echo e($car['name']); ?> - Drive&amp;Go</title>
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
    <h2>Rent the <?php echo e($car['brand']." ".$car['name']); ?></h2>
    <p style="text-align:center; margin-bottom:10px;">
        Price per day: <b>$<?php echo e($car['price']); ?></b>
    </p>

    <?php
    if ($msg != "") {
        if (strpos($msg, "confirmed") !== false) {
            echo "<div class='message success'>$msg</div>";
        } else {
            echo "<div class='message error'>$msg</div>";
        }
    }
    ?>

    <?php if (count($bookedList) > 0) { ?>
        <div class="unavailable-box">
            <h3>Currently unavailable on:</h3>
            <ul>
            <?php foreach ($bookedList as $b) { ?>
                <li>From <b><?php echo e($b['start_date']); ?></b> to <b><?php echo e($b['end_date']); ?></b></li>
            <?php } ?>
            </ul>
            <p class="hint">Please choose dates outside the periods above.</p>
        </div>
    <?php } else { ?>
        <div class="available-box">This car is fully available right now.</div>
    <?php } ?>

    <form method="post" action="rent.php?id=<?php echo (int)$car_id; ?>">
        <?php echo csrf_field(); ?>
        <label>Start Date</label>
        <input type="date" name="start_date" required>

        <label>End Date</label>
        <input type="date" name="end_date" required>

        <input type="submit" name="submit" value="Confirm Rental" class="btn">
    </form>
</div>

<footer class="footer">
    &copy; <?php echo date('Y'); ?> Drive&amp;Go
</footer>

</body>
</html>
