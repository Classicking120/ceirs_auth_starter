<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// Restrict access to admins only
if (!isset($_SESSION['admin']) || $_SESSION['admin']['role'] !== 'admin') {
    redirect('admin_login.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $incident_id = intval($_POST['incident_id']);
    $new_status = $_POST['status'] ?? 'pending';

    $stmt = $mysqli->prepare("UPDATE incidents SET status = ? WHERE incident_id = ?");
    $stmt->bind_param("si", $new_status, $incident_id);
    $stmt->execute();
    $stmt->close();

    set_flash("Incident updated to $new_status ✅");
    redirect('admin_dashboard.php');
    exit;
}

// Pagination setup
$per_page = intval($_GET['per_page'] ?? 20);
$page     = max(1, intval($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

// Search filter
$search = trim($_GET['search'] ?? '');
$search_sql = "";
if ($search !== '') {
    $like = "%" . $mysqli->real_escape_string($search) . "%";
    $search_sql = "AND (u.name LIKE '$like' OR i.incident_type LIKE '$like' OR i.location LIKE '$like' OR i.status LIKE '$like' OR i.incident_code LIKE '$like')";
}

// Count total incidents (for pagination)
$count_sql = "
    SELECT COUNT(*) as total
    FROM incidents i
    JOIN users u ON i.user_id = u.user_id
    WHERE 1=1 $search_sql
";
$total_rows = $mysqli->query($count_sql)->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $per_page);

// Fetch paginated incidents
$sql = "
    SELECT i.incident_id, i.incident_code, i.media, u.phone AS reporter, i.incident_type, i.description, 
           i.location, i.reported_at, i.status 
    FROM incidents i
    JOIN users u ON i.user_id = u.user_id
    WHERE 1=1 $search_sql
    ORDER BY i.reported_at DESC
    LIMIT $per_page OFFSET $offset
";

$incidents = $mysqli->query($sql);

// Summaries
function getIncidentSummary($mysqli, $period = "WEEK") {
    $sql = "
        SELECT incident_type, COUNT(*) as total 
        FROM incidents
        WHERE reported_at >= DATE_SUB(NOW(), INTERVAL 1 $period) 
        GROUP BY incident_type
    ";
    $result = $mysqli->query($sql);
    return $result ?: [];
}

$weekly  = getIncidentSummary($mysqli, "WEEK");
$monthly = getIncidentSummary($mysqli, "MONTH");

// Status Overview
$statusCounts = $mysqli->query("SELECT status, COUNT(*) as total FROM incidents GROUP BY status");
$statuses = ['pending'=>0, 'responding'=>0, 'resolved'=>0];
while ($s = $statusCounts->fetch_assoc()) {
    $statuses[strtolower($s['status'])] = $s['total'];
}

$flash = get_flash();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CEIRS - Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?= e(BASE_URL) ?>/assets/css/styles.css" rel="stylesheet">

</head>
<body>
  <nav class="nav">
    <div class="container">
      <img src="images/ogitech.jpeg" alt="Logo" class="brand-logo">
      <div class="brand"><a href="<?= e(BASE_URL) ?>/index.php">CEIRS</a></div>
    </div>
  </nav>

  <main class="container">
    <?php if ($flash): ?>
      <div class="flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

<div class="topbar">
    <h1>⚙️ Admin</h1>
   <a href="logout.php" class="logout">Logout</a>
</div>

<p>Welcome, <strong><?= e($_SESSION['admin']['name']) ?></strong> (<?= e($_SESSION['admin']['department']) ?>)</p>

<div class="controls">
    <!-- Search form -->
    <form method="get">
        <input type="text" name="search" placeholder="🔍 Search by ID, reporter, type, location, status" value="<?= e($search) ?>">
        <select name="per_page">
            <option value="10" <?= $per_page==10?'selected':'' ?>>10</option>
            <option value="20" <?= $per_page==20?'selected':'' ?>>20</option>
            <option value="50" <?= $per_page==50?'selected':'' ?>>50</option>
        </select>
        <button type="submit" class="btn">Apply</button>
        <?php if ($search !== ''): ?>
            <a href="admin_dashboard.php" class="btn btn-print">Clear Search</a>
        <?php endif; ?>
    </form>
</div>

<h2>📋 Reported Incidents</h2>
<table>
    <tr>
        <th>Incident ID</th>
        <th>Reporter</th>
        <th>Type</th>
        <th>Description</th>
        <th>Media</th>
        <th>Location</th>
        <th>Date/Time</th>
        <th>Status</th>
        <th>Update</th>
    </tr>
    <?php if ($incidents && $incidents->num_rows > 0): ?>
        <?php while ($row = $incidents->fetch_assoc()): ?>
        <tr>
            <td><?= e($row['incident_code']) ?></td>
            <td>📞<?= e($row['reporter']) ?></td>
            <td><?= ucfirst($row['incident_type']) ?></td>
            <td><?= e($row['description']) ?></td>
            <td><?php if ($row['media']): ?>
              <a href="<?= e($row['media']) ?>" target="_blank">
                  <img src="<?= e($row['media']) ?>" alt="Media" style="width: 100px; height: auto; cursor: pointer;" onclick="openImageModal('<?= e($row['media']) ?>')">
              </a>
              <?php else: ?>
                No Media
              <?php endif; ?>
            </td>

            <td>📍<?= e($row['location']) ?></td>
            <td>⏰<?= $row['reported_at'] ?></td>
            <td>
                <?php
                  $status = strtolower($row['status']);
                  $icons = ["pending"=>"⏳","responding"=>"🚨","resolved"=>"✅"];
                  $icon = $icons[$status] ?? "❔";
                ?>
                <span class="status <?= $status ?>"><?= $icon . " " . ucfirst($status) ?></span>
            </td>
            <td>
                <form method="post" class="status-form">
                    <input type="hidden" name="incident_id" value="<?= $row['incident_id'] ?>">
                    <select name="status">
                        <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>⏳ Pending</option>
                        <option value="responding" <?= $row['status']=='responding'?'selected':'' ?>>🚨 Responding</option>
                        <option value="resolved" <?= $row['status']=='resolved'?'selected':'' ?>>✅ Resolved</option>
                    </select>
                    <button type="submit" name="update_status">Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="8">No incidents reported yet.</td></tr>
    <?php endif; ?>
</table>

<!-- Pagination -->
<div class="pagination">
  <a href="?page=<?= max(1,$page-1) ?>&per_page=<?= $per_page ?>&search=<?= urlencode($search) ?>" class="<?= $page<=1?'disabled':'' ?>">⬅ Prev</a>
  <span>Page <?= $page ?> of <?= $total_pages ?></span>
  <a href="?page=<?= min($total_pages,$page+1) ?>&per_page=<?= $per_page ?>&search=<?= urlencode($search) ?>" class="<?= $page>=$total_pages?'disabled':'' ?>">Next ➡</a>
</div>

<!-- Status Overview -->
<h2>📊 Status Overview</h2>
<div class="summary-grid">
  <div class="circle-card pending"><div class="number"><?= $statuses['pending'] ?></div><p>⏳ Pending</p></div>
  <div class="circle-card responding"><div class="number"><?= $statuses['responding'] ?></div><p>🚨 Responding</p></div>
  <div class="circle-card resolved"><div class="number"><?= $statuses['resolved'] ?></div><p>✅ Resolved</p></div>
</div>

<!-- Weekly -->
<h2>📊 Weekly Summary</h2>
<div class="summary-grid">
  <?php if ($weekly && $weekly->num_rows > 0): while ($row = $weekly->fetch_assoc()): ?>
    <div class="circle-card"><div class="number"><?= $row['total'] ?></div><p><?= ucfirst($row['incident_type']) ?></p></div>
  <?php endwhile; else: ?><p>No incidents this week.</p><?php endif; ?>
</div>

<!-- Monthly -->
<h2>📊 Monthly Summary</h2>
<div class="summary-grid">
  <?php if ($monthly && $monthly->num_rows > 0): while ($row = $monthly->fetch_assoc()): ?>
    <div class="circle-card"><div class="number"><?= $row['total'] ?></div><p><?= ucfirst($row['incident_type']) ?></p></div>
  <?php endwhile; else: ?><p>No incidents this month.</p><?php endif; ?>
</div>

<div>
    <button onclick="window.print()" class="btn btn-print">🖨️ Print</button>
</div>

<!-- Image Modal -->
<div id="imageModal" style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.8); z-index: 9999; justify-content: center; align-items: center;">
    <span style="position: absolute; top: 20px; right: 20px; font-size: 30px; color: white; cursor: pointer;" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" style="max-width: 90%; max-height: 90%; margin: auto; display: block;">
</div>

<script>
function openImageModal(imageSrc) {
    // Set the modal image source
    document.getElementById("modalImage").src = imageSrc;
    // Display the modal
    document.getElementById("imageModal").style.display = "flex";
}

function closeImageModal() {
    // Hide the modal
    document.getElementById("imageModal").style.display = "none";
}
</script>


<?php include __DIR__ . '/footer.php'; ?>
