<?php
$page_title = 'Assignments';
include 'db.php';
include 'auth.php';
require_role('student');
include 'header.php';

$uid = current_user()['id'];
// Only assignments from courses the student is enrolled in (FR-9)
$rows = db_all($conn,
    "SELECT a.id, a.title, a.description, a.due_date, c.course_code, c.course_name,
            s.completed_at
     FROM assignments a
     JOIN enrollments e ON e.course_id = a.course_id AND e.student_id = ?
     JOIN courses c ON c.id = a.course_id
     LEFT JOIN assignment_status s ON s.assignment_id = a.id AND s.student_id = ?
     ORDER BY a.due_date", "ii", [$uid, $uid]);

$today = date('Y-m-d');
$soon = date('Y-m-d', strtotime('+2 days'));
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Assignments</h2>

<?php if ($rows): ?>
    <div class="table-wrap">
    <table class="data-table">
        <tr>
            <th>Course</th><th>Assignment</th><th>Description</th><th>Due Date</th><th>Status</th><th>Action</th>
        </tr>
        <?php foreach ($rows as $r) {
            $done = $r['completed_at'] !== null;
            $class = '';
            if (!$done && $r['due_date'] < $today) { $class = 'overdue'; }
            elseif (!$done && $r['due_date'] <= $soon) { $class = 'due-soon'; }
        ?>
        <tr class="<?php echo $class; ?>">
            <td><?php echo e($r['course_code'] . " - " . $r['course_name']); ?></td>
            <td><?php echo e($r['title']); ?></td>
            <td><?php echo e($r['description']); ?></td>
            <td><?php echo e($r['due_date']); ?></td>
            <td><?php echo $done ? 'Completed' : ($class === 'overdue' ? 'Overdue' : 'Pending'); ?></td>
            <td>
                <form action="handlers/toggle_assignment.php" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="assignment_id" value="<?php echo (int) $r['id']; ?>">
                    <input type="hidden" name="action" value="<?php echo $done ? 'undo' : 'complete'; ?>">
                    <button type="submit" class="button-small"><?php echo $done ? 'Undo' : 'Mark complete'; ?></button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php else: ?>
    <p>No assignments yet. <a href="courses_available.php">Enroll in a course</a> to see its assignments.</p>
<?php endif; ?>
</main>

<?php
mysqli_close($conn);
include 'includes/footer.php';
