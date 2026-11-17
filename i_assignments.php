<?php
include 'db.php';
include 'auth.php';
require_role('instructor');
include 'header.php';

$rows = db_all($conn,
    "SELECT a.id, a.title, a.due_date, c.course_code,
            (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = a.course_id) AS enrolled,
            (SELECT COUNT(*) FROM assignment_status s WHERE s.assignment_id = a.id) AS done
     FROM assignments a
     JOIN courses c ON c.id = a.course_id
     WHERE c.instructor_id = ?
     ORDER BY a.due_date", "i", [current_user()['id']]);
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Assignments</h2>

<?php if ($rows): ?>
    <div class="table-wrap">
    <table class="data-table">
        <tr><th>Course</th><th>Assignment</th><th>Due Date</th><th>Completed</th><th>Action</th></tr>
        <?php foreach ($rows as $r) { ?>
        <tr>
            <td><?php echo e($r['course_code']); ?></td>
            <td><?php echo e($r['title']); ?></td>
            <td><?php echo e($r['due_date']); ?></td>
            <td><?php echo (int) $r['done']; ?> of <?php echo (int) $r['enrolled']; ?> students</td>
            <td><a class="button-small button-link" href="edit_assignments.php?id=<?php echo (int) $r['id']; ?>">Edit</a></td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php else: ?>
    <p>No assignments yet. <a href="assignment_form.php">Create one</a>.</p>
<?php endif; ?>
</main>

<?php
mysqli_close($conn);
