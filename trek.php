<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM treks WHERE id=?');
$stmt->bind_param('i',$id); $stmt->execute(); $trip = $stmt->get_result()->fetch_assoc();
if(!$trip) { header('Location: list.php'); exit; }
$stmt = $conn->prepare('SELECT filename FROM trek_images WHERE trek_id=? ORDER BY is_cover DESC, id ASC');
$stmt->bind_param('i',$id); $stmt->execute(); $imgs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
include 'header.php';
?>
<h1 style="margin-top:18px"><?=htmlspecialchars($trip['title'])?></h1>
<div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:20px">
  <div style="flex:1;min-width:320px"><img src="images/<?=htmlspecialchars($imgs[0]['filename'] ?? 'trek_1.jpg')?>" style="width:100%;border-radius:10px"></div>
  <div style="flex:1;min-width:320px">
    <h2>₹<?=number_format($trip['price'],2)?></h2>
    <p><strong>Location:</strong> <?=htmlspecialchars($trip['location'])?></p>
    <p><strong>Duration:</strong> <?=htmlspecialchars($trip['duration'])?></p>
    <p><strong>Difficulty:</strong> <?=htmlspecialchars($trip['difficulty'])?></p>
    <p><?=nl2br(htmlspecialchars($trip['long_desc']))?></p>
    <a class="btn" href="book_form.php?trek_id=<?=$trip['id']?>">Book Now</a>
  </div>
</div>

<h3>Gallery</h3>
<div style="display:flex;gap:10px;flex-wrap:wrap">
  <?php foreach($imgs as $im): ?>
    <img src="images/<?=htmlspecialchars($im['filename'])?>" style="width:180px;height:120px;object-fit:cover;border-radius:8px">
  <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>