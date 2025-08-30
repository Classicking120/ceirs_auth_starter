<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$flash = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CEIRS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <link href="<?= e(BASE_URL) ?>/assets/css/styles.css" rel="stylesheet">
</head>
<body>
  <nav class="nav">
    <div class="container nav__container">
      <div class="brand">
        <img src="<?= e(BASE_URL) ?>/images/ogitech.jpeg" alt="Logo" class="brand-logo">
        <a href="<?= e(BASE_URL) ?>/index.php">CEIRS</a>
      </div>
      
      <button class="nav-toggle" aria-label="open navigation">
        <span class="hamburger"></span>
      </button>

      <div class="nav__menu">
        <ul class="menu">
          <?php if (is_logged_in()): $u = current_user(); ?>
            <li><a href="<?= e(BASE_URL) ?>/dashboard.php">Dashboard</a></li>
            <li><a href="<?= e(BASE_URL) ?>/report_incident.php">Report Incident</a></li>
            <li><a href="<?= e(BASE_URL) ?>/my_incidents.php">My Incidents</a></li>
            <li><a href="<?= e(BASE_URL) ?>/logout.php" class="btn btn--danger">Logout</a></li>
          <?php else: ?>
            <li><a href="<?= e(BASE_URL) ?>/login.php">Login</a></li>
            <li><a href="<?= e(BASE_URL) ?>/register.php">Register</a></li>
            <li><a href="<?= e(BASE_URL) ?>/admin_login.php">Admin</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container">
    <?php if ($flash): ?>
      <div class="flash flash--<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>