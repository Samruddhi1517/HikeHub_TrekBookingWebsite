<?php
require 'db.php';

$trek_id = intval($_GET['trek_id'] ?? 0);
$stmt = $conn->prepare('SELECT id,title,price FROM treks WHERE id=?');
$stmt->bind_param('i',$trek_id);
$stmt->execute();
$trip = $stmt->get_result()->fetch_assoc();
if(!$trip) { header('Location: list.php'); exit; }

include 'header.php';
?>

<style>
    .booking-wrapper {
        max-width: 650px;
        margin: 30px auto;
    }
    .booking-card {
        background: #fff;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .booking-card h2 {
        margin: 0 0 15px;
        font-size: 26px;
        font-weight: 700;
    }
    .booking-card p.desc {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .form-group input {
        padding: 11px 12px;
        border-radius: 10px;
        border: 1px solid #dfe4ea;
        font-size: 15px;
    }
    .price-box, .total-box {
        margin-top: 20px;
        padding: 14px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 10px;
        display: flex; 
        justify-content: space-between;
        align-items: center;
        font-size: 16px;
    }
    .price-box strong, .total-box strong {
        font-size: 20px;
        color: #0b74de;
    }
    .btn-primary {
        display: block;
        width: 100%;
        margin-top: 20px;
        padding: 12px 0;
        font-size: 17px;
        border: none;
        border-radius: 12px;
        background: #0b74de;
        color: #fff;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-primary:hover {
        background: #095bb0;
    }
</style>

<div class="booking-wrapper">

    <div class="booking-card">
        <h2>Book Your Trip</h2>
        <p class="desc">Fill the details below to confirm your booking for <strong><?= htmlspecialchars($trip['title']) ?></strong>.</p>

        <form method="post" action="book_action.php">
            <input type="hidden" name="trek_id" value="<?= $trip['id'] ?>">

            <div class="form-group">
                <label>Your Name</label>
                <input name="name" required placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label>Your Email</label>
                <input name="email" type="email" required placeholder="example@gmail.com">
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input name="phone" required placeholder="9876543210">
            </div>

            <div class="form-group">
                <label>Travel Date</label>
                <input name="travel_date" type="date" required>
            </div>

            <div class="form-group">
                <label>Number of Seats</label>
                <input id="seats" name="seats" type="number" min="1" value="1" required>
            </div>

            <div class="price-box">
                <span>Price per person:</span>
                <strong>₹<?= number_format($trip['price'],2) ?></strong>
            </div>

            <div class="total-box">
                <span>Total Price:</span>
                <strong id="total_price">₹<?= number_format($trip['price'],2) ?></strong>
            </div>

            <button class="btn-primary" type="submit">Confirm Booking</button>
        </form>
    </div>

</div>

<script>
    let price = <?= $trip['price'] ?>;
    
    document.getElementById('seats').addEventListener('input', function() {
        let seats = this.value;
        if (seats < 1) seats = 1;
        let total = price * seats;
        document.getElementById('total_price').innerText = "₹" + total.toLocaleString();
    });
</script>

<?php include 'footer.php'; ?>
