<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

if (is_logged_in()) {
    redirect('dashboard.php');
} else {
    redirect('login.php');
}
?>