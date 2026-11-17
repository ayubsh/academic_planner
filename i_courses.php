<?php
include 'db.php';
include 'auth.php';
require_role('instructor');
include 'header.php';

$iid = current_user()['id'];
$courses = db_all($conn,
    "SELECT c.course_code, c.course_name,
            (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) AS students,
            (SELECT COUNT(*) FROM assignments a WHERE a.course_id = c.id) AS assignments
     FROM courses c WHERE c.instructor_id = ? ORDER BY c.course_code", "i", [$iid]);
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>My Courses</h2>

<?php if ($courses): ?>
    <div class="table-wrap">
    <table class="data-table">
        <tr><th>Code</th><th>Course</th><th>Students</th><th>Assignments</th></tr>
        <?php foreach ($courses as $c) { ?>
        <tr>
            <td><?php echo e($c['course_code']); ?></td>
            <td><?php echo e($c['course_name']); ?></td>
            <td><?php echo (int) $c['students']; ?></td>
            <td><?php echo (int) $c['assignments']; ?></td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php else: ?>
    <p>You have not created any courses yet.</p>
<?php endif; ?>

<h3>Create a course</h3>
<form class="form-card" action="add_course.php" method="post">
    <?php echo csrf_field(); ?>

    <label for="course_code">Course code</label>
    <input type="text" id="course_code" name="course_code" maxlength="20" placeholder="SE 250" value="<?php echo old('course_code'); ?>" required>

    <label for="course_name">Course name</label>
    <input type="text" id="course_name" name="course_name" maxlength="80" value="<?php echo old('course_name'); ?>" required>

    <input type="submit" class="button" value="Create course">
</form>
</main>

<?php
mysqli_close($conn);
include '../includes/footer.php';
