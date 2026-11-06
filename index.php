<?php  
$conn = mysqli_connect("localhost", "root", "", "academic_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT id, title, course_id, due_date, completed FROM tasks WHERE completed = 0 ORDER BY title";
$result = mysqli_query($conn, $sql);

?>

<link rel="stylesheet" href="style.css">
<main class="container">

<section class="hero">
    <h2>Welcome back, student1</h2>
    <p>Keep every assignment, deadline and task for all your classes in one place.</p>
</section>

<section class="stats">
    <div class="card">
        <h3>Completion</h3>
        <p class="big-number">33%</p>
    </div>
    <div class="card">
        <h3>Completed Tasks</h3>
        <p class="big-number">1</p>
    </div>
    <div class="card">
        <h3>Pending Tasks</h3>
        <p class="big-number">2</p>
    </div>
    <div class="card">
        <h3>Courses</h3>
        <p class="big-number">3</p>
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


