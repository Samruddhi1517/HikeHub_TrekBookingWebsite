<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trip Booking</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="site-header">
  <div class="container">

    
    <a href="list.php" class="logo">🏔️ HikeHub</a>

    <nav class="nav">
    <?php if(isset($_SESSION['user_id'])): ?>
      <span style="color:#fff;margin-right:10px">Hi, <?= htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="dashboard.php">My Bookings</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
    </nav>

  </div>
</header>
<main class="container">
