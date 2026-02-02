<?php
session_start();
require '../db.php';
if($_SERVER['REQUEST_METHOD']==='POST') {
  $user = $_POST['username']; $pass = $_POST['password'];
  $stmt = $conn->prepare('SELECT id, password FROM users WHERE username=? AND role="admin"');
  $stmt->bind_param('s',$user); $stmt->execute(); $r = $stmt->get_result()->fetch_assoc();
  if($r && password_verify($pass, $r['password'])) { $_SESSION['admin_id']=$r['id']; header('Location: dashboard.php'); exit; } else { $error='Invalid'; }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Login</title><link rel="stylesheet" href="../assets/styles.css"></head><body>
<div class="container" style="padding:40px"><h2>Admin Login</h2>
<?php if(!empty($error)) echo '<div style="color:red">Invalid</div>'; ?>
<form method="post"><input name="username" placeholder="username"><input name="password" type="password" placeholder="password"><button class="btn" type="submit">Login</button></form></div></body></html>
