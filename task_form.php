<?php
$page_title = 'Add Task';
include 'db.php';
include 'auth.php';
require_role('student');
include 'header.php';

$courses = db_all($conn,
    "SELECT c.id, c.course_code, c.course_name FROM enrollments e JOIN courses c ON c.id = e.course_id
     WHERE e.student_id = ? ORDER BY c.course_code", "i", [current_user()['id']]);
?>

<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Add Personal Task</h2>

<?php if (!$courses): ?>
    <p>You need to be enrolled in a course before adding tasks. <a href="courses_available.php">Find a course</a>.</p>
<?php else: ?>
<form class="form-card" action="add_task.php" method="post">
    <?php echo csrf_field(); ?>

    <label for="title">Task</label>
    <input type="text" id="title" name="title" maxlength="80" value="<?php echo old('title'); ?>" required>

    <label for="course_id">Course</label>
    <select id="course_id" name="course_id" required>
        <option value="">-- Select a course --</option>
        <?php foreach ($courses as $c) { ?>
            <option value="<?php echo (int) $c['id']; ?>"><?php echo e($c['course_code'] . " - " . $c['course_name']); ?></option>
        <?php } ?>
    </select>

    <label for="due_date">Due Date</label>
    <input type="date" id="due_date" name="due_date" required>

    <input type="submit" class="button" value="Save task">
</form>
<?php endif; ?>
</main>
<?php
mysqli_close($conn);
include 'includes/footer.php';
