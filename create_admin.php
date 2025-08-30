<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// --- ADMIN DETAILS  ---
$name        = "campus security";
$department  = "Security Dept";
$contactEmail = "soultrinity91@gmail.com";
$contactPhone = "07056374443";
$loginEmail  = "soultrinity91@gmail.com";   // for login
$plainPassword = "admin123";         // login password

// --- HASH PASSWORD (same way as student/staff accounts) ---
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// --- Check if admin already exists ---
$stmt = $mysqli->prepare("SELECT authority_id FROM authorities WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $loginEmail);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("❌ Admin with email $loginEmail already exists.");
}
$stmt->close();

// --- Insert into authorities table ---
$sql = "INSERT INTO authorities (name, department, contact_email, contact_phone, email, password) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssssss", $name, $department, $contactEmail, $contactPhone, $loginEmail, $hashedPassword);

if ($stmt->execute()) {
    echo "✅ Admin created successfully!<br>";
    echo "Use Email: <b>$loginEmail</b><br>";
    echo "Use Password: <b>$plainPassword</b>";
} else {
    echo "❌ Error creating admin: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
