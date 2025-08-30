<?php
// Common helper functions and session bootstrap
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple flash messaging
function set_flash($message, $type = 'success') {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}
function get_flash() {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Simple redirect helper
function redirect($path) {
    $base = rtrim(constant('BASE_URL'), '/');
    header('Location: ' . $base . '/' . ltrim($path, '/'));
    exit;
}

// Basic sanitization for output
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Authentication helpers
function is_logged_in() {
    return !empty($_SESSION['user']);
}
function current_user() {
    return $_SESSION['user'] ?? null;
}
function require_login() {
    if (!is_logged_in()) {
        set_flash('Please log in to continue.', 'warning');
        redirect('login.php');
    }
}

// CSRF protection
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Incident code generator
function generateIncidentCode($mysqli) {
    $year = date("Y");
    $prefix = "INC";

    // Find the last code used this year
    $stmt = $mysqli->prepare("SELECT incident_code FROM incidents WHERE incident_code LIKE ? ORDER BY incident_id DESC LIMIT 1");
    $like = "$prefix-$year-%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $lastCode = $result->fetch_assoc()['incident_code'] ?? null;
    $stmt->close();

    $nextNumber = 1;
    if ($lastCode) {
        $parts = explode("-", $lastCode); // [INC, YYYY, NNN]
        $nextNumber = intval($parts[2]) + 1;
    }

    return sprintf("%s-%s-%03d", $prefix, $year, $nextNumber);
}

?>