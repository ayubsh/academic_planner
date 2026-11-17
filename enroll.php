<?php
include 'db.php';
include 'auth.php';
require_role('student');
require_post('./courses_available.php');

$uid = current_user()['id'];
$course_id = (int) ($_POST['course_id'] ?? 0);

$course = db_one($conn, "SELECT course_code FROM courses WHERE id = ?", "i", [$course_id]);
if (!$course) {
    set_flash("That course does not exist.", 'error');
    redirect('./courses_available.php');
}

try {
    db_query($conn, "INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)", "ii", [$uid, $course_id]);
    set_flash("You are now enrolled in " . $course['course_code'] . ".");
} catch (mysqli_sql_exception $ex) {
    set_flash("You are already enrolled in " . $course['course_code'] . ".", 'error');
}
redirect('./my_courses.php');
