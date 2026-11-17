<?php
include 'db.php';
include 'auth.php';
require_role('instructor');
include 'header.php';

$iid = current_user()['id'];
$courses = db_all($conn, "SELECT id, course_code, course_name FROM courses WHERE instructor_id = ? ORDER BY course_code", "i", [$iid]);

// Selected course must belong to this instructor; default to the first one
$course_id = (int) ($_GET['course_id'] ?? 0);
$ids = array_column($courses, 'id');
if (!in_array($course_id, array_map('intval', $ids), true)) {
    $course_id = $courses ? (int) $courses[0]['id'] : 0;
}

$students = [];
$total = 0;
if ($course_id) {
    $total = (int) db_one($conn, "SELECT COUNT(*) AS n FROM assignments WHERE course_id = ?", "i", [$course_id])['n'];
    $students = db_all($conn,
        "SELECT u.full_name, u.username,
                (SELECT COUNT(*) FROM assignment_status s
                  JOIN assignments a ON a.id = s.assignment_id
                  WHERE a.course_id = e.course_id AND s.student_id = u.id) AS done
         FROM enrollments e JOIN users u ON u.id = e.student_id
         WHERE e.course_id = ? ORDER BY u.full_name", "i", [$course_id]);
}
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Student Progress</h2>

<?php if (!$courses): ?>
    <p>Create a course first. <a href="courses.php">Go to courses</a>.</p>
<?php else: ?>
    <form class="filter-form" action="i_progress.php" method="get">
        <label for="course_id">Course</label>
        <select id="course_id" name="course_id">
            <?php foreach ($courses as $c) { ?>
                <option value="<?php echo (int) $c['id']; ?>"<?php echo selected($course_id, $c['id']); ?>><?php echo e($c['course_code'] . " - " . $c['course_name']); ?></option>
            <?php } ?>
        </select>
        <button type="submit" class="button-small">View</button>
    </form>

    <?php if ($students): ?>
        <div class="table-wrap">
        <table class="data-table">
            <tr><th>Student</th><th>Completed</th><th>Pending</th><th>Percent</th></tr>
            <?php foreach ($students as $s) {
                $pct = $total > 0 ? (int) round($s['done'] / $total * 100) : 0; ?>
            <tr>
                <td><?php echo e($s['full_name']); ?> (<?php echo e($s['username']); ?>)</td>
                <td><?php echo (int) $s['done']; ?></td>
                <td><?php echo $total - (int) $s['done']; ?></td>
                <td><?php echo $pct; ?>%</td>
            </tr>
            <?php } ?>
        </table>
        </div>
        <p class="muted"><?php echo $total; ?> assignment(s) in this course.</p>
    <?php else: ?>
        <p>No students are enrolled in this course yet.</p>
    <?php endif; ?>
<?php endif; ?>
</main>

<?php
mysqli_close($conn);
