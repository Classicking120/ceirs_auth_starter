<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/send_mail.php';
require_login();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Invalid request. Please reload the page.';
    }

    $type = $_POST['incident_type'] ?? '';
    $desc = trim($_POST['description'] ?? '');
    $loc  = trim($_POST['location'] ?? '');
    $user = current_user();

    if (!in_array($type, ['fire','medical','security','accident'])) $errors[] = 'Invalid incident type.';
    if ($desc === '') $errors[] = 'Description is required.';
    if ($loc === '') $errors[] = 'Location is required.';

    // Handle optional file upload
    $mediaPath = null;
    if (!empty($_FILES['media']['name'])) {
        $targetDir = __DIR__ . '/uploads/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['media']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['media']['tmp_name'], $targetFile)) {
            $mediaPath = 'uploads/' . $fileName;
        } else {
            $errors[] = 'File upload failed.';
        }
    }

    if (!$errors) {
        // Generate custom incident code from functions.php
        $incident_code = generateIncidentCode($mysqli);

        $stmt = $mysqli->prepare("INSERT INTO incidents (incident_code, user_id, incident_type, description, location, media, reported_at, status) 
                                  VALUES (?,?,?,?,?,?,NOW(),'pending')");
        $stmt->bind_param("sissss", $incident_code, $user['user_id'], $type, $desc, $loc, $mediaPath);

        if ($stmt->execute()) {
            // Send alert emails to all authorities
            $auths = $mysqli->query("SELECT contact_email FROM authorities");
            while ($a = $auths->fetch_assoc()) {
                $subject = "Campus Emergency Alert – " . ucfirst($type) . " Incident";
                $body = "
                    <h2>New Incident Reported</h2>
                    <p><strong>Incident ID:</strong> $incident_code</p>
                    <p><strong>Type:</strong> $type</p>
                    <p><strong>Description:</strong> $desc</p>
                    <p><strong>Location:</strong> $loc</p>
                    <p><strong>Reported By:</strong> " . e($user['name']) . "</p>
                    <p><em>Reported at: " . date('Y-m-d H:i:s') . "</em></p>
                ";
                send_alert($a['contact_email'], $subject, $body);
            }

            set_flash("Incident reported successfully. Your ID is $incident_code", 'success');
            redirect('my_incidents.php');
        } else {
            $errors[] = 'Failed to save incident.';
        }
        $stmt->close();
    }
}
?>
<?php include __DIR__ . '/header.php'; ?>

<h1>Report Incident</h1>
<form method="post" action="" enctype="multipart/form-data" class="card">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

  <label>
    <span>Incident Type</span>
    <select name="incident_type" required>
      <option value="">-- Select Type --</option>
      <option value="fire">Fire</option>
      <option value="medical">Medical</option>
      <option value="security">Security</option>
      <option value="accident">Accident</option>
    </select>
  </label>

  <label>
    <span>Description</span>
    <textarea name="description" rows="4" required></textarea>
  </label>

  <label>
    <span>Location</span>
    <input type="text" name="location" required>
  </label>

  <label>
    <span>Upload Evidence (optional)</span>
    <input type="file" name="media" accept="image/*,video/*">
  </label>

  <?php if ($errors): ?>
    <div class="error-list">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= e($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <button type="submit">Submit Report</button>
</form>

<?php include __DIR__ . '/footer.php'; ?>
