<?php
require 'db.php';

$booking_id = intval($_GET['booking_id']);
$payment_id = $_GET['payment_id'] ?? '';

$stmt = $conn->prepare("UPDATE bookings 
                        SET status='paid' 
                        WHERE id=?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
?>
<!DOCTYPE html>
<html>
<head><title>Payment Successful</title></head>
<body>
<h1>Payment Successful!</h1>
<p>Your Payment ID: <?=htmlspecialchars($payment_id)?></p>
<p>Booking #<?=$booking_id?> is now confirmed.</p>
<p><a href="list.php">Back to Treks</a></p>
</body>
</html>
