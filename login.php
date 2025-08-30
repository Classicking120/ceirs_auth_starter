<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Invalid request. Please reload the page.';
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if ($password === '') $errors[] = 'Password is required.';

    if (!$errors) {
        $stmt = $mysqli->prepare('SELECT user_id, name, email, phone, role, password FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            // Save user session (without password)
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
            ];
            set_flash('Welcome back, ' . $user['name'] . '!');
            redirect('dashboard.php');
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>

<?php include __DIR__ . '/header.php'; ?>

<h1>Login</h1>
<form method="post" action="" class="card">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <label>
    <span>Email</span>
    <input type="email" name="email" value="<?= e($email) ?>" required>
  </label>
  <label>
    <span>Password</span>
    <input type="password" name="password" required>
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

  <button type="submit">Login</button>
  <p class="muted">No account? <a href="register.php">Register</a></p>
</form>

<?php include __DIR__ . '/footer.php'; ?>
