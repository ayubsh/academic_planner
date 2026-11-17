<?php
include 'db.php';
include 'auth.php';
require_role('instructor');
include 'header.php';

$courses = db_all($conn, "SELECT id, course_code, course_name FROM courses WHERE instructor_id = ? ORDER BY course_code",
    "i", [current_user()['id']]);
$course_now = $_SESSION['old']['course_id'] ?? '';
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Create Assignment</h2>

<?php if (!$courses): ?>
    <p>Create a course first. <a href="courses.php">Go to courses</a>.</p>
<?php else: ?>
<form class="form-card" action="add_assignments.php" method="post">
    <?php echo csrf_field(); ?>

    <label for="course_id">Course</label>
    <select id="course_id" name="course_id" required>
        <option value="">-- Select a course --</option>
        <?php foreach ($courses as $c) { ?>
            <option value="<?php echo (int) $c['id']; ?>"<?php echo selected($course_now, $c['id']); ?>><?php echo e($c['course_code'] . " - " . $c['course_name']); ?></option>
        <?php } ?>
    </select>

    <label for="title">Title</label>
    <input type="text" id="title" name="title" maxlength="80" value="<?php echo old('title'); ?>" required>

    <label for="description">Description (optional)</label>
    <textarea id="description" name="description" maxlength="255" rows="4"><?php echo old('description'); ?></textarea>

    <label for="due_date">Due Date</label>
    <input type="date" id="due_date" name="due_date" value="<?php echo old('due_date'); ?>" required>

    <input type="submit" class="button" value="Post assignment">
</form>
<?php endif; ?>
</main>

<?php
mysqli_close($conn);
