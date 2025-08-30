<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

session_destroy();
session_start(); // restart to set flash
set_flash('You have been logged out.', 'info');
redirect('login.php');
?>