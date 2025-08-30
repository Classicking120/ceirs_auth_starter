<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';
require_login();

$user = current_user();
$stmt = $mysqli->prepare("SELECT * FROM incidents WHERE user_id = ? ORDER BY reported_at DESC");
$stmt->bind_param("i", $user['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$incidents = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<?php include __DIR__ . '/header.php'; ?>

<h1>My Incidents</h1>
<div class="card">
  <?php if (!$incidents): ?>
    <p>No incidents reported yet.</p>
  <?php else: ?>
    <table border="0" cellpadding="8" cellspacing="0" width="100%">
      <tr>
        <th>Type</th>
        <th>Description</th>
        <th>Location</th>
        <th>Status</th>
        <th>Date</th>
        <th>Media</th>
      </tr>
      <?php foreach ($incidents as $inc): ?>
        <tr>
          <td><?= e($inc['incident_type']) ?></td>
          <td><?= e($inc['description']) ?></td>
          <td><?= e($inc['location']) ?></td>
          <td><?= e($inc['status']) ?></td>
          <td><?= e($inc['reported_at']) ?></td>
          <td>
            <?php if ($inc['media']): ?>
              <a href="<?= e($inc['media']) ?>" target="_blank">View</a>
            <?php else: ?>
              None
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
