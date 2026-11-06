<?php
$conn = mysqli_connect("localhost", "root", "", "academic_planner");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$title = $_POST["title"] ?? '';
$due_date = $_POST["due_date"] ?? '';

$sql = "INSERT INTO tasks (user_id, course_id, title, due_date) 
        VALUES (1,1,'$title', '$due_date')";

if (mysqli_query($conn, $sql)) {
    header("Location: index.php");
    exit();
} else {
    echo "Database Error: " . mysqli_error($conn);
}
?>
