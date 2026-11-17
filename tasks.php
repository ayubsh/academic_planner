<?php
$conn = mysqli_connect("localhost", "root", "", "academic_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$student_id = 1; // demo student until login is built

$stmt = mysqli_prepare($conn,
    "SELECT t.id, t.title, t.due_date, t.completed, c.course_code
     FROM tasks t
     JOIN courses c ON t.course_id = c.id
     WHERE t.user_id = ?
     ORDER BY t.completed, t.due_date");
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row_count = mysqli_num_rows($result);


?>

<link rel="stylesheet" href="style.css">
<main class="container">

<h2>My Tasks</h2>

<?php if ($row_count > 0): ?>
<table class="data-table">
    <tr>
        <th>Course</th>
        <th>Task</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row["course_code"]); ?></td>
            <td><?php echo htmlspecialchars($row["title"]); ?></td>
            <td><?php echo htmlspecialchars($row["due_date"]); ?></td>
            <td><?php echo $row["completed"] ? "Completed" : "Pending"; ?></td>
            <td class="actions">
                <?php if (!$row["completed"]): ?>
                <form action="update_task.php" method="post">
                    <input type="hidden" name="id" value="<?php echo (int) $row["id"]; ?>">
                    <button type="submit" class="button-small">Mark complete</button>
                </form>
                <?php endif; ?>
                <form action="delete_task.php" method="post" onsubmit="return confirm('Delete this task?');">
                    <input type="hidden" name="id" value="<?php echo (int) $row["id"]; ?>">
                    <button type="submit" class="button-small button-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
</table>
<?php else: ?>
    <p>No tasks yet. <a href="task_form.php">Add your first task</a>.</p>
<?php endif; ?>


</main>
