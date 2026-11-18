<?php
include 'db.php';
include 'auth.php';
require_role('instructor');
require_post('courses.php');

$iid = current_user()['id'];
$code = trim($_POST['course_code'] ?? '');
$name = trim($_POST['course_name'] ?? '');
$old = ['course_code' => $code, 'course_name' => $name];

$errors = [];
if ($code === '' || text_len($code) > 20) {
    $errors[] = "Course code is required (20 characters max).";
}
if ($name === '' || text_len($name) > 80) {
    $errors[] = "Course name is required (80 characters max).";
}
if ($errors) {
    fail_back($errors, 'i_courses.php', $old);
}

try {
    db_query($conn, "INSERT INTO courses (course_code, course_name, instructor_id) VALUES (?, ?, ?)",
        "ssi", [$code, $name, $iid]);
} catch (mysqli_sql_exception $ex) {
    fail_back("You already have a course with the code " . $code . ".", '../instructor/courses.php', $old);
}
set_flash("Course " . $code . " created.");
redirect('i_courses.php');
