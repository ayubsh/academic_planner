<?php
include 'db.php';
include 'auth.php';
require_role('student');
include 'header.php';

$uid = current_user()['id'];
$courses = db_all($conn,
    "SELECT c.id, c.course_code, c.course_name, u.full_name AS instructor,
            (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id AND e.student_id = ?) AS enrolled
     FROM courses c
     JOIN users u ON u.id = c.instructor_id
     ORDER BY c.course_code", "i", [$uid]);
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Available Courses</h2>

<?php if ($courses): ?>
    <div class="table-wrap">
    <table class="data-table">
        <tr><th>Code</th><th>Course</th><th>Instructor</th><th>Action</th></tr>
        <?php foreach ($courses as $c) { ?>
        <tr>
            <td><?php echo e($c['course_code']); ?></td>
            <td><?php echo e($c['course_name']); ?></td>
            <td><?php echo e($c['instructor']); ?></td>
            <td>
                <?php if ($c['enrolled']): ?>
                    Enrolled
                <?php else: ?>
                    <form action="enroll.php" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="course_id" value="<?php echo (int) $c['id']; ?>">
                        <button type="submit" class="button-small">Enroll</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php else: ?>
    <p>No courses have been created yet. Check back after an instructor adds one.</p>
<?php endif; ?>
</main>

<?php
mysqli_close($conn);
