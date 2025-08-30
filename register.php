<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

$errors = [];
$name = '';
$email = '';
$phone = '';
$role = 'student'; // default

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Invalid request. Please reload the page.';
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? 'student');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '') $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if ($password === '' || strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';
    if (!in_array($role, ['student','staff'])) $errors[] = 'Invalid role selected.';

    // Check if email already exists
    if (!$errors) {
        $stmt = $mysqli->prepare('SELECT user_id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'An account with that email already exists.';
        }
        $stmt->close();
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $name, $email, $phone, $role, $hash);
        if ($stmt->execute()) {
            set_flash('Registration successful. You can now log in.');
            redirect('login.php');
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
        $stmt->close();
    }
}
?>

<?php include __DIR__ . '/header.php'; ?>

<h1>Create Account</h1>
<form method="post" action="" class="card">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <label>
    <span>Name</span>
    <input type="text" name="name" value="<?= e($name) ?>" required>
  </label>
  <label>
    <span>Email</span>
    <input type="email" name="email" value="<?= e($email) ?>" required>
  </label>
  <label>
    <span>Phone</span>
    <input type="text" name="phone" value="<?= e($phone) ?>">
  </label>
  <label>
    <span>Role</span>
    <select name="role" required>
      <option value="student" <?= $role==='student'?'selected':''; ?>>Student</option>
      <option value="staff" <?= $role==='staff'?'selected':''; ?>>Staff</option>
    </select>
  </label>
  <label>
    <span>Password</span>
    <input type="password" name="password" required>
  </label>
  <label>
    <span>Confirm Password</span>
    <input type="password" name="confirm_password" required>
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

  <button type="submit">Register</button>
  <p class="muted">Already have an account? <a href="login.php">Login</a></p>
</form>

<?php include __DIR__ . '/footer.php'; ?>
