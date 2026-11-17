<?php
// Small shared helpers: escaping, redirects, flash messages, CSRF, form repopulation.

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Flash message shown once on the next page. $msg may be a string or an array of strings.
function set_flash($msg, $type = 'success') {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function show_flash() {
    if (empty($_SESSION['flash'])) {
        return;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $class = $flash['type'] === 'error' ? 'notice error' : 'notice';
    $messages = (array) $flash['msg'];
    echo "<div class='" . $class . "'>";
    if (count($messages) > 1) {
        echo "<ul>";
        foreach ($messages as $m) {
            echo "<li>" . e($m) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>" . e($messages[0]) . "</p>";
    }
    echo "</div>";
}

// Send the user back to a form with error messages and the values they typed.
function fail_back($errors, $url, $old = []) {
    $_SESSION['old'] = $old;
    set_flash($errors, 'error');
    redirect($url);
}

// Previously typed value for a form field (escaped). Cleared by footer.php.
function old($key, $default = '') {
    return e($_SESSION['old'][$key] ?? $default);
}

function selected($a, $b) {
    return ((string) $a === (string) $b) ? ' selected' : '';
}

function valid_date($d) {
    $dt = DateTime::createFromFormat('Y-m-d', $d);
    return $dt && $dt->format('Y-m-d') === $d;
}

// CSRF protection for every POST form
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    return "<input type='hidden' name='csrf' value='" . csrf_token() . "'>";
}

function verify_csrf() {
    $ok = isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
    if (!$ok) {
        http_response_code(400);
        die('Invalid or expired form. Go back, refresh the page and try again.');
    }
}

// Handlers only accept POST
function require_post($fallback) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect($fallback);
    }
    verify_csrf();
}

// Character count that works with or without the mbstring extension
function text_len($s) {
    return function_exists('mb_strlen') ? mb_strlen($s) : strlen($s);
}
