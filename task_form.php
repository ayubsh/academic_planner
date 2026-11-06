
<link rel="stylesheet" href="style.css">


<main class="container">

<h2>Add Personal Task</h2>

<form class="form-card" action="add_task.php" method="post">
    <label for="title">Task</label>
    <input type="text" id="title" name="title" maxlength="80" required>

    <label for="course_id">Course</label>
    <select id="course_id" name="course_id" required>
        <option value="">-- Select a course --</option>
        <option value="1">SE 250 - Software Engineering</option>
        <option value="2">CS 301 - Data Structures</option>
        <option value="3">ENG 101 - College Composition</option>
    </select>

    <label for="due_date">Due Date</label>
    <input type="date" id="due_date" name="due_date" required>

    <input type="submit" class="button" value="Save task">
</form>
</main>

<?php 
echo "<script>console.log('Debug Objects:  ' );</script>";
?>
