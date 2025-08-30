<?php
// ===== CEIRS CONFIG =====
// Adjust these values to match your XAMPP/WAMP environment.
define('DB_HOST', 'localhost');
define('DB_NAME', 'ceirs_db');  // Make sure you created this database in phpMyAdmin
define('DB_USER', 'root');      // Default XAMPP user
define('DB_PASS', '');          // Default XAMPP password is empty. Change if you set one.

define('BASE_URL', 'http://localhost/ceirs_auth_starter'); // Update if your folder name/path differs

// ===== EMAIL CONFIG =====
// Use your Gmail or institutional SMTP settings
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USERNAME', 'mojooc48@gmail.com'); // your email
define('MAIL_PASSWORD', 'bhem dltc pccn soki');  // use App Password, not real password
define('MAIL_PORT', 465);
define('MAIL_FROM', 'ceirs@gmail.com');
define('MAIL_FROM_NAME', 'CEIRS System');
?>