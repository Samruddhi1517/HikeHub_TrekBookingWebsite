<?php
session_start();
require '../db.php';
if(!isset($_SESSION['admin_id'])) header('Location: login.php');
if($_SERVER['REQUEST_METHOD']==='POST') {
  $title=$_POST['title']; $slug=$_POST['slug']; $price=floatval($_POST['price']);
  $short=$_POST['short_desc']; $long=$_POST['long_desc']; $loc=$_POST['location']; $duration=$_POST['duration'];
  $stmt = $conn->prepare('INSERT INTO treks (title,slug,short_desc,long_desc,price,location,duration) VALUES (?,?,?,?,?,?,?)');
  $stmt->bind_param('sssdsss',$title,$slug,$short,$long,$price,$loc,$duration);
  if($stmt->execute()) {
    $tid = $stmt->insert_id;
    if(!empty($_FILES['images'])) {
      foreach($_FILES['images']['tmp_name'] as $i=>$tmp) {
        if($tmp) {
          $name = basename($_FILES['images']['name'][$i]);
          $target = '../images/'.time().'_'.$name;
          move_uploaded_file($tmp, $target);
          $is_cover = ($i==0)?1:0;
          $stmt2 = $conn->prepare('INSERT INTO trek_images (trek_id,filename,is_cover) VALUES (?,?,?)');
          $stmt2->bind_param('isi',$tid, basename($target), $is_cover);
          $stmt2->execute();
        }
      }
    }
    header('Location: dashboard.php'); exit;
  } else { $err='Save failed'; }
}
include '../header.php';
?>
<h1 style="margin-top:18px">Add Trek</h1>
<form method="post" enctype="multipart/form-data" style="max-width:800px">
  <label>Title</label><input name="title" required>
  <label>Slug (url)</label><input name="slug" required>
  <label>Price</label><input name="price" required>
  <label>Location</label><input name="location" required>
  <label>Duration</label><input name="duration" required>
  <label>Short description</label><textarea name="short_desc"></textarea>
  <label>Long description</label><textarea name="long_desc"></textarea>
  <label>Images (first will be cover)</label><input type="file" name="images[]" multiple>
  <button class="btn" type="submit">Save</button>
</form>
<?php include '../footer.php'; ?>