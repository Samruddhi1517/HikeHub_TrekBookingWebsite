<?php
require 'db.php';

$search = $_GET['q'] ?? '';
$location = $_GET['location'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$where = ['1=1']; 
$params = []; 
$types = '';

// Search
if ($search !== '') { 
    $where[] = '(title LIKE ? OR short_desc LIKE ?)';
    $params[] = '%'.$search.'%'; 
    $params[] = '%'.$search.'%'; 
    $types .= 'ss'; 
}

// Location Filter
if ($location !== '') { 
    $where[] = 'location = ?'; 
    $params[] = $location; 
    $types .= 's'; 
}

$where_sql = implode(' AND ', $where);

// Sorting
$order_by = 't.created_at DESC';
if ($sort === 'price_asc') $order_by = 't.price ASC';
if ($sort === 'price_desc') $order_by = 't.price DESC';
if ($sort === 'popular') $order_by = 't.is_featured DESC, t.created_at DESC';

// Final Query (NO LIMIT — NO PAGINATION)
$sql = "
    SELECT 
        t.id, t.title, t.short_desc, t.price, t.location, t.duration,
        (SELECT filename FROM trek_images WHERE trek_id=t.id AND is_cover=1 LIMIT 1) AS cover
    FROM treks t
    WHERE $where_sql
    ORDER BY $order_by
";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();
$items = $res->fetch_all(MYSQLI_ASSOC);

include 'header.php';
?>

<h1 style="margin-top:18px">Upcoming Treks</h1>

<form method="get" class="controls">
  <input class="search-bar" name="q" placeholder="Search treks" value="<?=htmlspecialchars($search)?>">
  
  <select name="location">
      <option value="">All locations</option>
      <option value="Pune" <?=($location=='Pune')?'selected':''?>>Pune</option>
      <option value="Lonavala" <?=($location=='Lonavala')?'selected':''?>>Lonavala</option>
      <option value="Malshej" <?=($location=='Malshej')?'selected':''?>>Malshej</option>
  </select>

  <select name="sort">
      <option value="newest" <?=($sort=='newest')?'selected':''?>>Newest</option>
      <option value="popular" <?=($sort=='popular')?'selected':''?>>Most Popular</option>
      <option value="price_asc" <?=($sort=='price_asc')?'selected':''?>>Price: Low to High</option>
      <option value="price_desc" <?=($sort=='price_desc')?'selected':''?>>Price: High to Low</option>
  </select>

  <button class="btn">Apply</button>
</form>

<div class="grid">
<?php foreach($items as $it): ?>
  <div class="card">
    <img src="images/<?=htmlspecialchars($it['cover'] ?: 'trek_1.jpg')?>" alt="">
    <div class="body">
      <h3 class="title"><?=htmlspecialchars($it['title'])?></h3>
      <div class="meta"><?=htmlspecialchars($it['location'])?> • <?=htmlspecialchars($it['duration'])?></div>
      <p class="small"><?=htmlspecialchars($it['short_desc'])?></p>
    </div>

    <div class="price-row">
      <div><strong>₹<?=number_format($it['price'], 2)?></strong></div>
      <a class="btn" href="trek.php?id=<?=$it['id']?>">View</a>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>
