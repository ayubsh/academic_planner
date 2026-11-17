<?php
include 'db.php';
include 'auth.php';
require_role('student');
include 'header.php';

$uid = current_user()['id'];
$courses = db_all($conn,
    "SELECT c.course_code, c.course_name, u.full_name AS instructor
     FROM enrollments e
     JOIN courses c ON c.id = e.course_id
     JOIN users u ON u.id = c.instructor_id
     WHERE e.student_id = ?
     ORDER BY c.course_code", "i", [$uid]);
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>My Courses</h2>

<?php if ($courses): ?>
    <div class="table-wrap">
    <table class="data-table">
        <tr><th>Code</th><th>Course</th><th>Instructor</th></tr>
        <?php foreach ($courses as $c) { ?>
        <tr>
            <td><?php echo e($c['course_code']); ?></td>
            <td><?php echo e($c['course_name']); ?></td>
            <td><?php echo e($c['instructor']); ?></td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php else: ?>
    <p>You are not enrolled in any courses yet. <a href="courses_available.php">Find a course</a>.</p>
<?php endif; ?>
</main>

<?php
mysqli_close($conn);
