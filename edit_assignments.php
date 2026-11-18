<?php
include 'db.php';
include 'auth.php';
require_role('instructor');

$id = (int) ($_GET['id'] ?? 0);
// Ownership check: the assignment must belong to one of this instructor's courses
$a = db_one($conn,
    "SELECT a.id, a.title, a.description, a.due_date, c.course_code, c.course_name
     FROM assignments a JOIN courses c ON c.id = a.course_id
     WHERE a.id = ? AND c.instructor_id = ?", "ii", [$id, current_user()['id']]);
if (!$a) {
    set_flash("That assignment was not found.", 'error');
    redirect('assignments.php');
}
include 'header.php';
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Edit Assignment</h2>

<form class="form-card" action="update_assignments.php" method="post">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo (int) $a['id']; ?>">
    <p class="muted">Course: <strong><?php echo e($a['course_code'] . " - " . $a['course_name']); ?></strong></p>

    <label for="title">Title</label>
    <input type="text" id="title" name="title" maxlength="80" value="<?php echo old('title', $a['title']); ?>" required>

    <label for="description">Description (optional)</label>
    <textarea id="description" name="description" maxlength="255" rows="4"><?php echo old('description', $a['description']); ?></textarea>

    <label for="due_date">Due Date</label>
    <input type="date" id="due_date" name="due_date" value="<?php echo old('due_date', $a['due_date']); ?>" required>

    <input type="submit" class="button" value="Save changes">
    <a href="assignments.php">Cancel</a>
</form>
</main>

<?php
mysqli_close($conn);
