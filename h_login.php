<?php
include 'db.php';
include 'auth.php';
require_post('login.php');

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$user = db_one($conn, "SELECT id, username, full_name, password, role FROM users WHERE username = ?", "s", [$username]);

// One generic message so attackers cannot tell which usernames exist
if (!$user || !password_verify($password, $user['password'])) {
    fail_back("Wrong username or password.", 'login.php', ['username' => $username]);
}

session_regenerate_id(true);
$_SESSION['user'] = [
    'id' => (int) $user['id'],
    'username' => $user['username'],
    'full_name' => $user['full_name'],
    'role' => $user['role'],
];
set_flash("Welcome back, " . $user['full_name'] . "!");
redirect('index.php')_;
