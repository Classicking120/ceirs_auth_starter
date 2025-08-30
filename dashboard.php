<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';
require_login();

$user = current_user();
?>
<?php include __DIR__ . '/header.php'; ?>


<h1>Dashboard</h1>

<div class="card">
  <p><strong>Name:</strong> <?= e($user['name']) ?></p>
  <p><strong>Email:</strong> <?= e($user['email']) ?></p>
  <p><strong>Role:</strong> <?= e($user['role']) ?></p>
</div>

<div class="card">
  <p><a href="logout.php" class="logout">Logout</a></p> 
</div>

<?php include __DIR__ . '/footer.php'; ?>

