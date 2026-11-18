<?php
include 'db.php';
include 'auth.php';
require_role('student');
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect('task_form.php');
}
verify_csrf();
include 'header.php';

$uid = current_user()['id'];
$title = trim($_POST["title"] ?? '');
$course_id = (int) ($_POST["course_id"] ?? 0);
$due_date = trim($_POST["due_date"] ?? '');
?>
<link rel="stylesheet" href="style.css">
  <main class="container">
  <?php
$errors = [];
if ($title === '' || text_len($title) > 80) {
    $errors[] = "Task title is required (80 characters max).";
}
if (!db_one($conn, "SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?", "ii", [$uid, $course_id])) {
    $errors[] = "Please choose one of your enrolled courses.";
}
if ($due_date === '') {
    $errors[] = "Due date is required.";
} elseif (!valid_date($due_date)) {
    $errors[] = "Due date is not a valid date.";
}

if ($errors) {
    echo "<h2>Task not saved</h2><ul class='notice error'>";
    foreach ($errors as $err) {
        echo "<li>" . e($err) . "</li>";
    }
    echo "</ul><a class='button' href='./task_form.php'>Go back</a>";
} else {
    db_query($conn, "INSERT INTO tasks (user_id, course_id, title, due_date, completed) VALUES (?, ?, ?, ?, 0)",
        "iiss", [$uid, $course_id, $title, $due_date]);
    echo "<h2>Task saved!</h2>";
    echo "<p class='notice'>Added <strong>" . e($title) . "</strong>, due " . e($due_date) . ".</p>";
    echo "<a class='button' href='./tasks.php'>View my tasks</a> ";
    echo "<a class='button button-secondary' href='./task_form.php'>Add another</a>";
} ?>
</main>

mysqli_close($conn);
