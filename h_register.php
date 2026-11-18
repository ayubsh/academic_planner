<?php
include 'db.php'
include 'auth.php';
require_post('./register.php');

$username = trim($_POST['username'] ?? '');
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';
$role = $_POST['role'] ?? '';

// Never send passwords back to the form
$old = ['username' => $username, 'full_name' => $full_name, 'email' => $email, 'role' => $role];

$errors = [];
if (!preg_match('/^[A-Za-z0-9_.-]{3,40}$/', $username)) {
    $errors[] = "Username must be 3-40 characters: letters, numbers, dot, dash or underscore.";
}
if ($full_name === '' || text_len($full_name) > 80) {
    $errors[] = "Full name is required (80 characters max).";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || text_len($email) > 100) {
    $errors[] = "Please enter a valid email address.";
}
if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters.";
}
if ($password !== $confirm) {
    $errors[] = "Passwords do not match.";
}
if (!in_array($role, ['student', 'instructor'], true)) {
    $errors[] = "Please choose student or instructor.";
}

if (!$errors) {
    if (db_one($conn, "SELECT id FROM users WHERE username = ?", "s", [$username])) {
        $errors[] = "That username is already taken.";
    }
    if (db_one($conn, "SELECT id FROM users WHERE email = ?", "s", [$email])) {
        $errors[] = "An account with that email already exists.";
    }
}

if ($errors) {
    fail_back($errors, '../register.php', $old);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
try {
    db_query($conn, "INSERT INTO users (username, full_name, email, password, role) VALUES (?, ?, ?, ?, ?)",
        "sssss", [$username, $full_name, $email, $hash, $role]);
} catch (mysqli_sql_exception $ex) {
    // Two people registering the same name at the same moment
    fail_back("That username or email is already in use.", 'register.php', $old);
}

set_flash("Account created. Please log in.");
redirect('login.php');
