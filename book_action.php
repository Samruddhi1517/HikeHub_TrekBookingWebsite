<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$trek_id     = intval($_POST['trek_id']);
$name        = trim($_POST['name']);
$email       = trim($_POST['email']);
$phone       = trim($_POST['phone']);
$travel_date = $_POST['travel_date'];
$seats       = intval($_POST['seats']);

// GET PRICE OF TREK
$stmt = $conn->prepare("SELECT price FROM treks WHERE id=?");
$stmt->bind_param("i", $trek_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    die("Invalid Trek ID!");
}

$amount = $row['price'] * $seats;

// GET LOGGED-IN USER ID (if logged in)
$user_id = $_SESSION['user_id'] ?? null;
$status  = "pending";

// INSERT BOOKING
$stmt = $conn->prepare("
    INSERT INTO bookings 
    (trek_id, user_id, name, email, phone, travel_date, seats, amount_paid, status)
    VALUES (?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "iisssisss",
    $trek_id,
    $user_id,
    $name,
    $email,
    $phone,
    $travel_date,
    $seats,
    $amount,
    $status
);

$stmt->execute();
$booking_id = $conn->insert_id;

// REDIRECT TO PAYMENT PAGE
header("Location: payment.php?booking_id=" . $booking_id);
exit;
?>


?>
<h1 style="margin-top:18px">Booking Confirmed</h1>
<div class="card" style="padding:18px;max-width:700px">
  <p>Thank you, <?=htmlspecialchars($name)?>. Your booking ID is <strong>#<?=$bid?></strong>.</p>
  <p>We will contact you at <?=htmlspecialchars($email)?> or <?=htmlspecialchars($phone)?> to confirm details.</p>
  <p><a href="list.php" class="btn">Back to Treks</a></p>
</div>
<?php include 'footer.php'; ?>