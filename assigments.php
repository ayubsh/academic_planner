<?php  
$conn = mysqli_connect("localhost", "root", "", "academic_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT a.title, a.description, a.due_date, c.course_code, c.course_name
        FROM assignments a
        JOIN courses c ON a.course_id = c.id
        ORDER BY a.due_date";
$result = mysqli_query($conn, $sql);
$row_count = mysqli_num_rows($result);
?>

<link rel="stylesheet" href="style.css">
<?php require 'header.php'?>
<main class="container">
  <h1>Assigments</h1>
<?php if ($row_count > 0): ?>
    <table class="data-table">
        <tr>
            <th>Course</th>
            <th>Assignment</th>
            <th>Description</th>
            <th>Due Date</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row["course_code"] . " - " . $row["course_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["title"]); ?></td>
            <td><?php echo htmlspecialchars($row["description"]); ?></td>
            <td><?php echo htmlspecialchars($row["due_date"]); ?></td>
        </tr>
        <?php } ?>
    </table>
<?php else: ?>
    <p>No assignments have been posted yet.</p>
<?php endif; ?>


</main>

<?php 
mysqli_close($conn); 
?>
