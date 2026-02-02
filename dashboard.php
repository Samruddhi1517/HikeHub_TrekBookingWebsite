<?php
session_start();
require '../db.php';
if(!isset($_SESSION['admin_id'])) header('Location: login.php');
$bookings = $conn->query('SELECT b.*, t.title FROM bookings b JOIN treks t ON b.trek_id=t.id ORDER BY b.booking_date DESC')->fetch_all(MYSQLI_ASSOC);
include '../header.php';
?>
<h1 style="margin-top:18px">Admin Dashboard</h1>
<p><a class="btn" href="add_trek.php">Add Trek</a></p>
<h3>Recent Bookings</h3>
<table border="1" cellpadding="8" style="width:100%;border-collapse:collapse">
<tr><th>ID</th><th>Trek</th><th>Name</th><th>Email</th><th>Phone</th><th>Date</th><th>Seats</th><th>Amount</th><th>Status</th></tr>
<?php foreach($bookings as $b): ?>
<tr><td><?=$b['id']?></td><td><?=htmlspecialchars($b['title'])?></td><td><?=htmlspecialchars($b['name'])?></td><td><?=htmlspecialchars($b['email'])?></td><td><?=htmlspecialchars($b['phone'])?></td><td><?=htmlspecialchars($b['travel_date'])?></td><td><?=$b['seats']?></td><td>₹<?=number_format($b['amount_paid'],2)?></td><td><?=htmlspecialchars($b['status'])?></td></tr>
<?php endforeach; ?>
</table>
<?php include '../footer.php'; ?>