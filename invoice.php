<?php
session_start();
require 'db.php';

$booking_id = intval($_GET['booking_id']);

$stmt = $conn->prepare("
    SELECT b.*, t.title, t.price
    FROM bookings b
    JOIN treks t ON t.id = b.trek_id
    WHERE b.id=?
");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if(!$data){
    die("Invalid Booking ID!");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Invoice</title>

<style>
body{
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #0b74de, #47b5ff);
    padding:30px;
    margin:0;
}

.invoice-container{
    max-width:850px;
    margin:auto;
    background:rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border-radius:25px;
    padding:40px;
    box-shadow:0 20px 40px rgba(0,0,0,0.2);
}

.header{
    text-align:center;
    margin-bottom:20px;
}

.header h1{
    font-size:34px;
    margin:0;
    color:#0b74de;
    font-weight:800;
}

.header p{
    font-size:15px;
    margin-top:5px;
    color:#444;
}

.section-title{
    font-size:20px;
    font-weight:600;
    margin-top:25px;
    color:#0b74de;
    border-left:5px solid #0b74de;
    padding-left:10px;
}

.invoice-table{
    width:100%;
    margin-top:15px;
    border-collapse:collapse;
}

.invoice-table th{
    text-align:left;
    padding:12px;
    background:#0b74de;
    color:white;
    border-radius:8px 8px 0 0;
    font-size:15px;
}

.invoice-table td{
    padding:12px;
    background:white;
    border-bottom:1px solid #eee;
    font-size:15px;
}

.total-box{
    background:#e8f3ff;
    padding:20px;
    border-radius:15px;
    margin-top:25px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.total-box h2{
    margin:0;
    font-size:22px;
    color:#0b74de;
}

.print-btn{
    margin-top:30px;
    display:block;
    width:100%;
    padding:15px;
    border:none;
    background:#0b74de;
    color:white;
    border-radius:12px;
    cursor:pointer;
    font-size:18px;
    font-weight:600;
    transition:0.3s;
}

.print-btn:hover{
    background:#095bb0;
}
</style>

</head>

<body>

<div class="invoice-container">

    <div class="header">
        <h1>Trek Booking Invoice</h1>
        <p>Your adventure begins! Here are your booking details.</p>
    </div>

    <div class="section-title">Booking Details</div>
    <table class="invoice-table">
        <tr><th>Field</th><th>Information</th></tr>
        <tr><td><strong>Booking ID</strong></td><td>#<?= $data['id'] ?></td></tr>
        <tr><td><strong>Customer Name</strong></td><td><?= htmlspecialchars($data['name']) ?></td></tr>
        <tr><td><strong>Email</strong></td><td><?= htmlspecialchars($data['email']) ?></td></tr>
        <tr><td><strong>Phone</strong></td><td><?= htmlspecialchars($data['phone']) ?></td></tr>
        <tr><td><strong>Trek</strong></td><td><?= htmlspecialchars($data['title']) ?></td></tr>
        <tr><td><strong>Travel Date</strong></td><td><?= $data['travel_date'] ?></td></tr>
        <tr><td><strong>Seats</strong></td><td><?= $data['seats'] ?></td></tr>
    </table>

    <div class="total-box">
        <h2>Total Paid: ₹<?= number_format($data['amount_paid'],2) ?></h2>
    </div>

    <button class="print-btn" onclick="window.print()">Download / Print Invoice</button>

</div>

</body>
</html>
