<?php
require_once __DIR__ . '/../db.php';
session_start();
include 'header.php';

// SQL QUERY
$query = "
    SELECT 
        b.id,
        b.seats AS people,
        b.status,
        b.txn_id,
        b.created_at,
        t.title,
        t.location
    FROM bookings b
    JOIN treks t ON b.trek_id = t.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
";

// PREPARE
$stmt = $conn->prepare($query);

// ❗ DEBUG: SHOW SQL ERROR IF PREPARE FAILED
if (!$stmt) {
    die("<h3 style='color:red'>SQL Prepare Failed:</h3>" . $conn->error . "<br><br><pre>$query</pre>");
}

// BIND
$stmt->bind_param("i", $_SESSION['user_id']);

// EXECUTE
if (!$stmt->execute()) {
    die("<h3 style='color:red'>SQL Execute Failed:</h3>" . $stmt->error);
}

$res = $stmt->get_result();
?>

<h2>Your Bookings</h2>
<div class="grid">
  <?php while ($r = $res->fetch_assoc()): ?>
  <div class="card">
    <div class="body">
      <h3><?= htmlspecialchars($r['title']) ?></h3>
      <p class="meta"><?= htmlspecialchars($r['location']) ?></p>
      <p>People: <?= intval($r['people']) ?></p>
      <p>Status: <strong><?= htmlspecialchars($r['status']) ?></strong></p>
      <?php if ($r['txn_id']): ?>
        <p>Txn: <?= htmlspecialchars($r['txn_id']) ?></p>
      <?php endif; ?>
      <p class="small">Booked at: <?= $r['created_at'] ?></p>
    </div>
  </div>
  <?php endwhile; ?>
</div>

<?php include 'footer.php'; ?>
