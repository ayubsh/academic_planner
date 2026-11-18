<?php
include 'db.php';
include 'auth.php';
include 'progress.php';
require_role('student');
include 'header.php';

$uid = current_user()['id'];
$stats = get_completion($conn, $uid);
$rows = db_all($conn,
    "SELECT a.title, a.due_date, c.course_code, s.completed_at
     FROM assignments a
     JOIN enrollments e ON e.course_id = a.course_id AND e.student_id = ?
     JOIN courses c ON c.id = a.course_id
     LEFT JOIN assignment_status s ON s.assignment_id = a.id AND s.student_id = ?
     ORDER BY a.due_date", "ii", [$uid, $uid]);

$completed = array_filter($rows, function ($r) { return $r['completed_at'] !== null; });
$pending = array_filter($rows, function ($r) { return $r['completed_at'] === null; });
$today = date('Y-m-d');
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>My Progress</h2>

<p><strong><?php echo $stats['percent']; ?>% complete</strong>
   (<?php echo $stats['done']; ?> of <?php echo $stats['total']; ?> assignments and tasks)</p>
<div class="progress-bar"><div class="progress-fill" data-percent="<?php echo $stats['percent']; ?>"></div></div>
<noscript><p class="muted">Enable JavaScript to see the bar fill.</p></noscript>

<h3>Pending assignments</h3>
<?php if ($pending): ?>
    <div class="table-wrap">
    <table class="data-table">
        <tr><th>Course</th><th>Assignment</th><th>Due Date</th></tr>
        <?php foreach ($pending as $r) { ?>
        <tr class="<?php echo $r['due_date'] < $today ? 'overdue' : ''; ?>">
            <td><?php echo e($r['course_code']); ?></td>
            <td><?php echo e($r['title']); ?></td>
            <td><?php echo e($r['due_date']); ?><?php echo $r['due_date'] < $today ? ' (overdue)' : ''; ?></td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php else: ?>
    <p>Nothing pending.</p>
<?php endif; ?>

<h3>Completed assignments</h3>
<?php if ($completed): ?>
    <div class="table-wrap">
    <table class="data-table">
        <tr><th>Course</th><th>Assignment</th><th>Due Date</th><th>Completed On</th></tr>
        <?php foreach ($completed as $r) { ?>
        <tr>
            <td><?php echo e($r['course_code']); ?></td>
            <td><?php echo e($r['title']); ?></td>
            <td><?php echo e($r['due_date']); ?></td>
            <td><?php echo e(substr($r['completed_at'], 0, 10)); ?></td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php else: ?>
    <p>No assignments completed yet.</p>
<?php endif; ?>

<script>
// Set the bar width from data-percent (keeps the HTML free of inline styles)
document.querySelectorAll('.progress-fill').forEach(function (el) {
    el.style.width = el.dataset.percent + '%';
});
</script>
  </main>

<?php
mysqli_close($conn);
