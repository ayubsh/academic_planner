<?php
include 'db.php';
include 'auth.php';
include 'progress.php';
include 'header.php';

$user = current_user();
?>

<link rel="stylesheet" href="style.css">
<main class="container">
<?php if (!$user): ?>

    <section class="hero">
        <h2>Plan your semester in one place</h2>
        <p>Students track assignments, tasks and progress. Instructors post assignments and see who is on track.</p>
    </section>
    <section class="quick-links">
        <a class="button" href="login.php">Log in</a>
        <a class="button button-secondary" href="register.php">Create an account</a>
    </section>

<?php elseif ($user['role'] === 'student'): ?>
    <?php
    $uid = $user['id'];
    $stats = get_completion($conn, $uid);
    $course_count = db_one($conn, "SELECT COUNT(*) AS c FROM enrollments WHERE student_id = ?", "i", [$uid])['c'];
    $upcoming = db_all($conn,
        "SELECT a.title, a.due_date, c.course_code
         FROM assignments a
         JOIN enrollments e ON e.course_id = a.course_id AND e.student_id = ?
         JOIN courses c ON c.id = a.course_id
         LEFT JOIN assignment_status s ON s.assignment_id = a.id AND s.student_id = ?
         WHERE s.id IS NULL
         ORDER BY a.due_date LIMIT 5", "ii", [$uid, $uid]);
    ?>
    <section class="hero">
        <h2>Welcome back, <?php echo e($user['full_name']); ?></h2>
        <p>Keep every assignment, deadline and task for all your classes in one place.</p>
    </section>

    <section class="stats">
        <div class="card"><h3>Completion</h3><p class="big-number"><?php echo $stats['percent']; ?>%</p></div>
        <div class="card"><h3>Completed</h3><p class="big-number"><?php echo $stats['done']; ?></p></div>
        <div class="card"><h3>Pending</h3><p class="big-number"><?php echo $stats['pending']; ?></p></div>
        <div class="card"><h3>Courses</h3><p class="big-number"><?php echo (int) $course_count; ?></p></div>
    </section>
    <p class="muted">Completion counts your assignments and personal tasks together.</p>

    <h3>Next up</h3>
    <?php if ($upcoming): ?>
        <div class="table-wrap">
        <table class="data-table">
            <tr><th>Course</th><th>Assignment</th><th>Due Date</th></tr>
            <?php foreach ($upcoming as $row) { ?>
            <tr>
                <td><?php echo e($row['course_code']); ?></td>
                <td><?php echo e($row['title']); ?></td>
                <td><?php echo e($row['due_date']); ?></td>
            </tr>
            <?php } ?>
        </table>
        </div>
    <?php else: ?>
        <p>Nothing pending. <a href="courses_available.php">Find a course</a> to get assignments.</p>
    <?php endif; ?>

    <section class="quick-links">
        <a class="button" href="assignments.php">View Assignments</a>
        <a class="button" href="tasks.php">View My Tasks</a>
        <a class="button button-secondary" href="task_form.php">Add a Task</a>
    </section>

<?php else: ?>
    <?php
    $iid = $user['id'];
    $c = db_one($conn, "SELECT COUNT(*) AS n FROM courses WHERE instructor_id = ?", "i", [$iid])['n'];
    $a = db_one($conn, "SELECT COUNT(*) AS n FROM assignments a JOIN courses c ON c.id = a.course_id WHERE c.instructor_id = ?", "i", [$iid])['n'];
    $s = db_one($conn, "SELECT COUNT(DISTINCT e.student_id) AS n FROM enrollments e JOIN courses c ON c.id = e.course_id WHERE c.instructor_id = ?", "i", [$iid])['n'];
    ?>
    <section class="hero">
        <h2>Welcome, <?php echo e($user['full_name']); ?></h2>
        <p>Create courses, post assignments and follow student progress.</p>
    </section>

    <section class="stats">
        <div class="card"><h3>My Courses</h3><p class="big-number"><?php echo (int) $c; ?></p></div>
        <div class="card"><h3>Assignments</h3><p class="big-number"><?php echo (int) $a; ?></p></div>
        <div class="card"><h3>Students</h3><p class="big-number"><?php echo (int) $s; ?></p></div>
    </section>

    <section class="quick-links">
        <a class="button" href="instructor/courses.php">Manage Courses</a>
        <a class="button" href="instructor/assignments.php">View Assignments</a>
        <a class="button button-secondary" href="instructor/assignment_form.php">New Assignment</a>
        <a class="button" href="instructor/progress.php">Student Progress</a>
    </section>
<?php endif; ?>
</main>

<?php
mysqli_close($conn);
include 'includes/footer.php';
