<?php
$conn = mysqli_connect("localhost", "root", "", "academic_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$courses = mysqli_query($conn, "SELECT id, course_code, course_name FROM courses ORDER BY course_code");
?>



<link rel="stylesheet" href="style.css">
<?php include "./header.php"
?>

<main class="container">

<h2>Add Personal Task</h2>

<form class="form-card" action="add_task.php" method="post">
    <label for="title">Task</label>
    <input type="text" id="title" name="title" maxlength="80" required>

    <label for="course_id">Course</label>
    <select id="course_id" name="course_id" required>
        <option value="">-- Select a course --</option>
        <?php while ($c = mysqli_fetch_assoc($courses)) { ?>
            <option value="<?php echo (int) $c['id']; ?>">
                <?php echo htmlspecialchars($c['course_code'] . " - " . $c['course_name']); ?>
            </option>
        <?php } ?>
    </select>

    <label for="due_date">Due Date</label>
    <input type="date" id="due_date" name="due_date" required>

    <input type="submit" class="button" value="Save task">
</form>
</main>

<?php 
echo "<script>console.log('Debug Objects:  ' );</script>";
?>
