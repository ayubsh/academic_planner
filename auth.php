<?php
// Session start and access control. Set $base ('' or '../') before including.
require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
$base = $base ?? '';

function current_user() {
    return $_SESSION['user'] ?? null;
}

function require_login() {
    global $base;
    if (!current_user()) {
        set_flash('Please log in to continue.', 'error');
        redirect($base . 'login.php');
    }
}

// Only allow users with the given role ('student' or 'instructor').
function require_role($role) {
    global $base, $page_title;
    require_login();
    if (current_user()['role'] !== $role) {
        http_response_code(403);
        $page_title = 'Access denied';
        include __DIR__ . '/header.php';
        echo "<h2>Access denied</h2><p class='notice error'>This page is for " . e($role) . " accounts only.</p>";
        echo "<a class='button' href='" . $base . "index.php'>Back to dashboard</a>";
        include __DIR__ . '/footer.php';
        exit();
    }
}
