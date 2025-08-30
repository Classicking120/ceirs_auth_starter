CEIRS Auth Starter (PHP + MySQL)

1) Create database:
   - In phpMyAdmin, create database: ceirs_db
   - Ensure your users table exists (see your project SQL)

2) Copy this folder to your web root:
   - XAMPP: C:\xampp\htdocs\ceirs_auth_starter
   - WAMP:  C:\wamp64\www\ceirs_auth_starter
   - Linux/Mac (XAMPP MAMP): htdocs path

3) Configure:
   - Open config.php and confirm DB credentials and BASE_URL path.

4) Visit in browser:
   - http://localhost/ceirs_auth_starter

5) Create an admin/security account manually (optional):
   - INSERT INTO users (name,email,phone,role,password)
     VALUES ('Admin', 'admin@example.com', '0000', 'admin', PASSWORD_HASH_HERE);
   - Generate a password hash by temporarily using:
     <?php echo password_hash('yourpassword', PASSWORD_DEFAULT); ?>
     Or register a normal account and then update role via phpMyAdmin.

6) Next steps:
   - Implement incident reporting, notifications, and admin dashboard.
