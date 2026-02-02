<?php
session_start();
require 'db.php';

$booking_id = intval($_GET['booking_id']);

$stmt = $conn->prepare("
    SELECT b.id, b.amount_paid, b.name AS guest_name, b.email AS guest_email, 
           t.title AS trek_title 
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

$amount = $data['amount_paid'] * 100;
$name  = $data['guest_name'] ?: "Guest";
$email = $data['guest_email'] ?: "";
$title = $data['trek_title'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Secure Payment | Trek Booking</title>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    background: #ffffff; /* PURE WHITE */
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Card Styling */
.payment-card {
    width: 420px;
    background: #ffffff;
    padding: 35px;
    border-radius: 20px;
    border: 1px solid #eaeaea;
    box-shadow: 0 6px 25px rgba(0,0,0,0.08);
    animation: fadeIn 0.6s ease;
    text-align: center;
}

.payment-card h2 {
    font-size: 26px;
    font-weight: 600;
    color: #0b74de;
    margin-bottom: 8px;
}

.payment-card p {
    font-size: 16px;
    color: #444;
    margin-top: 0;
}

/* Amount Display Box */
.amount-box {
    background: #f7faff;
    padding: 22px;
    border-radius: 16px;
    margin: 25px 0;
    border: 1px solid #dce9ff;
}

.amount-box span {
    font-size: 28px;
    font-weight: 700;
    color: #0b74de;
}

/* Pay Button */
.pay-btn {
    width: 100%;
    padding: 15px;
    border: none;
    background: #0b74de;
    color: white;
    border-radius: 12px;
    font-size: 18px;
    cursor: pointer;
    font-weight: 600;
    box-shadow: 0 6px 14px rgba(11,116,222,0.3);
    transition: all 0.3s ease;
}

.pay-btn:hover {
    background: #095bb0;
    transform: translateY(-2px);
}

/* Fade-in effect */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}


</style>
</head>

<body>

<div class="payment-card">
    <h2>Complete Your Payment</h2>
    <p><strong><?= htmlspecialchars($title) ?></strong></p>

    <div class="amount-box">
        <span>₹<?= number_format($data['amount_paid'], 2) ?></span>
        <div style="font-size:12px;color:#666;margin-top:5px;">Amount To Be Paid</div>
    </div>

    <button id="payBtn" class="pay-btn">Pay Securely</button>
</div>

<!-- ✅ JAVASCRIPT FIXED HERE -->
<script>
document.getElementById('payBtn').onclick = function() {

    var options = {
        "key": "rzp_test_Ih4U8xtkmPzKpQ",
        "amount": "<?=$amount?>",
        "currency": "INR",
        "name": "Trek Booking",
        "description": "Trek Reservation Payment",
        "image": "https://cdn-icons-png.flaticon.com/512/684/684908.png",

        "handler": function (response){
            window.location = "payment_success.php?booking_id=<?=$booking_id?>&payment_id=" + response.razorpay_payment_id;
        },

        "prefill": {
            "name": "<?=$name?>",
            "email": "<?=$email?>"
        },

        "theme": {
            "color": "#0b74de"
        }
    };

    var rzp1 = new Razorpay(options);

    rzp1.on("payment.failed", function (response) {
        alert("Payment Failed: " + response.error.description);
        console.log(response.error);
    });

    rzp1.open();
};
</script>

</body>
</html>
