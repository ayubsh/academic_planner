<?php  
$conn = mysqli_connect("localhost", "root", "", "academic_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT id, title, course_id, due_date, completed FROM tasks WHERE completed = 0 ORDER BY title";
$result = mysqli_query($conn, $sql);


?>
<main>
  <h1>Assigments</h1>

<table class="data-table">
  <tr>
      <th>Course</th>
    <th>title</th>
    <th>completed</th>
    <th>due_date</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
      <td><?php echo htmlspecialchars($row["course_id"]); ?></td>
      <td><?php echo htmlspecialchars($row["title"]); ?></td>
      <td><?php echo htmlspecialchars($row["completed"]); ?></td>
      <td><?php echo htmlspecialchars($row["due_date"]); ?></td>
 
  </tr>
  <?php } ?>
</table>
</main>

<?php 
mysqli_close($conn); 
?>
