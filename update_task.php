<?php
$conn = mysqli_connect("localhost", "root", "", "academic_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int) ($_POST["id"] ?? 0);
    $stmt = mysqli_prepare($conn, "UPDATE tasks SET completed = 1 WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}
header("Location: tasks.php?msg=" . urlencode("Task marked complete."));
exit();
