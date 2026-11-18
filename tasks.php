<?php
include 'db.php';
include 'auth.php';
require_role('student');
include 'header.php';

$uid = current_user()['id'];
$filter = (int) ($_GET['course_id'] ?? 0);

$courses = db_all($conn,
    "SELECT c.id, c.course_code FROM enrollments e JOIN courses c ON c.id = e.course_id
     WHERE e.student_id = ? ORDER BY c.course_code", "i", [$uid]);

$sql = "SELECT t.id, t.title, t.due_date, t.completed, c.course_code
        FROM tasks t JOIN courses c ON c.id = t.course_id
        WHERE t.user_id = ?";
$types = "i";
$params = [$uid];
if ($filter > 0) {
    $sql .= " AND t.course_id = ?";
    $types .= "i";
    $params[] = $filter;
}
$sql .= " ORDER BY t.completed, t.due_date";
$tasks = db_all($conn, $sql, $types, $params);
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>My Tasks</h2>

<form class="filter-form" action="tasks.php" method="get">
    <label for="course_id">Course</label>
    <select id="course_id" name="course_id">
        <option value="0">All courses</option>
        <?php foreach ($courses as $c) { ?>
            <option value="<?php echo (int) $c['id']; ?>"<?php echo selected($filter, $c['id']); ?>><?php echo e($c['course_code']); ?></option>
        <?php } ?>
    </select>
    <button type="submit" class="button-small">Filter</button>
</form>

<?php if ($tasks): ?>
    <div class="table-wrap">
    <table class="data-table">
        <tr><th>Course</th><th>Task</th><th>Due Date</th><th>Status</th><th>Actions</th></tr>
        <?php foreach ($tasks as $t) { ?>
        <tr>
            <td><?php echo e($t['course_code']); ?></td>
            <td><?php echo e($t['title']); ?></td>
            <td><?php echo e($t['due_date']); ?></td>
            <td><?php echo $t['completed'] ? 'Completed' : 'Pending'; ?></td>
            <td class="actions">
                <?php if (!$t['completed']): ?>
                <form action="update_task.php" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $t['id']; ?>">
                    <button type="submit" class="button-small">Mark complete</button>
                </form>
                <?php endif; ?>
                <a class="button-small button-link" href="task_edit.php?id=<?php echo (int) $t['id']; ?>">Edit</a>
                <form action="delete_task.php" method="post" onsubmit="return confirm('Delete this task?');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $t['id']; ?>">
                    <button type="submit" class="button-small button-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php else: ?>
    <p>No tasks here yet. <a href="task_form.php">Add a task</a>.</p>
<?php endif; ?>
</main>

<?php
mysqli_close($conn);
