<?php
// Shared database connection and small prepared-statement helpers.
//
// Example:  $rows = db_all($conn, "SELECT * FROM tasks WHERE user_id = ?", "i", [$uid]);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db_config = ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'academic_planner'];
if (file_exists(__DIR__ . '/../config.local.php')) {
    $db_config = array_merge($db_config, require __DIR__ . '/../config.local.php');
}

try {
    $conn = mysqli_connect($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
    mysqli_set_charset($conn, 'utf8mb4');
} catch (mysqli_sql_exception $ex) {
    error_log('Academic Planner DB connection failed: ' . $ex->getMessage());
    http_response_code(500);
    die('The planner cannot reach the database right now. Make sure MySQL is running and sql/academic_planner.sql has been imported.');
}

// Run a prepared statement and return the statement object.
function db_query($conn, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($conn, $sql);
    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return $stmt;
}

// All rows of a SELECT as an array of associative arrays.
function db_all($conn, $sql, $types = '', $params = []) {
    $stmt = db_query($conn, $sql, $types, $params);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

// First row of a SELECT, or null.
function db_one($conn, $sql, $types = '', $params = []) {
    $rows = db_all($conn, $sql, $types, $params);
    return $rows[0] ?? null;
}
