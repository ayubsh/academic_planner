<?php
include 'db.php';
include 'auth.php';
require_role('instructor');
require_post('assignments.php');

$iid = current_user()['id'];
$id = (int) ($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$due_date = trim($_POST['due_date'] ?? '');
$old = ['title' => $title, 'description' => $description, 'due_date' => $due_date];

$owned = db_one($conn,
    "SELECT a.id FROM assignments a JOIN courses c ON c.id = a.course_id
     WHERE a.id = ? AND c.instructor_id = ?", "ii", [$id, $iid]);
if (!$owned) {
    set_flash("That assignment was not found.", 'error');
    redirect('i_assignments.php');
}

$errors = [];
if ($title === '' || text_len($title) > 80) {
    $errors[] = "Title is required (80 characters max).";
}
if (text_len($description) > 255) {
    $errors[] = "Description must be 255 characters or fewer.";
}
if (!valid_date($due_date)) {
    $errors[] = "Please enter a valid due date.";
}
if ($errors) {
    fail_back($errors, 'edit_assignments.php?id=' . $id, $old);
}

db_query($conn, "UPDATE assignments SET title = ?, description = ?, due_date = ? WHERE id = ?",
    "sssi", [$title, $description, $due_date, $id]);
set_flash("Assignment updated.");
redirect('i_assignments.php');
