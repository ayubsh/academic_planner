<?php  
$conn = mysqli_connect("localhost", "root", "", "academic_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$student_id = 1; // demo student until login

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total, COALESCE(SUM(completed), 0) AS done FROM tasks WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$total = (int) $stats['total'];
$done = (int) $stats['done'];
$pending = $total - $done;
$percent = $total > 0 ? round($done / $total * 100) : 0;

$course_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM courses"))['c'];
?>

<link rel="stylesheet" href="style.css">

<?php require 'header.php' ?>
<main class="container">

<section class="hero">
    <h2>Welcome back, student1</h2>
    <p>Keep every assignment, deadline and task for all your classes in one place.</p>
</section>

<section class="stats">
    <div class="card">
        <h3>Completion</h3>
<p class="big-number"><?php echo $percent ?> %</p>
    </div>
    <div class="card">
        <h3>Completed Tasks</h3>
<p class="big-number"><?php echo $done ?></p>
    </div>
    <div class="card">
        <h3>Pending Tasks</h3>
<p class="big-number"><?php echo $pending?></p>
    </div>
    <div class="card">
        <h3>Courses</h3>
<p class="big-number"><?php echo $course_count?></p>
    </div>
</section>

<section class="quick-links">
    <a class="button" href="assigments.php">View Assignments</a>
    <a class="button" href="tasks.php">View My Tasks</a>
    <a class="button button-secondary" href="task_form.php">Add a Task</a>
</section>

</main>

<?php 
mysqli_close($conn); 
?>


