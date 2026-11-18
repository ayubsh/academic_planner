<?php
include 'db.php';
include 'auth.php';
require_role('instructor');
require_post('i_assignments_form.php');

$iid = current_user()['id'];
$course_id = (int) ($_POST['course_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$due_date = trim($_POST['due_date'] ?? '');
$old = ['course_id' => $course_id, 'title' => $title, 'description' => $description, 'due_date' => $due_date];

$errors = [];
if (!db_one($conn, "SELECT id FROM courses WHERE id = ? AND instructor_id = ?", "ii", [$course_id, $iid])) {
    $errors[] = "Please choose one of your courses.";
}
if ($title === '' || text_len($title) > 80) {
    $errors[] = "Title is required (80 characters max).";
}
if (text_len($description) > 255) {
    $errors[] = "Description must be 255 characters or fewer.";
}
if ($due_date === '' || !valid_date($due_date)) {
    $errors[] = "Please enter a valid due date.";
}
if ($errors) {
    fail_back($errors, 'i_assignments_form.php', $old);
}

db_query($conn, "INSERT INTO assignments (course_id, title, description, due_date) VALUES (?, ?, ?, ?)",
    "isss", [$course_id, $title, $description, $due_date]);
set_flash("Assignment posted. Enrolled students can see it now.");
redirect('i_assignments.php');
