<?php
// Set $page_title and $base ('' for root pages, '../' for handlers and instructor pages) before including.
$user = current_user();
?>

<header class="site-header">
    <h1 class="logo">Academic Planner</h1>
    <nav class="site-nav">
        <?php if (!$user): ?>
            <a href="<?php echo $base; ?>login.php">Log in</a>
            <a href="<?php echo $base; ?>register.php">Register</a>
        <?php elseif ($user['role'] === 'student'): ?>
            <a href="<?php echo $base; ?>index.php">Dashboard</a>
            <a href="<?php echo $base; ?>assignments.php">Assignments</a>
            <a href="<?php echo $base; ?>tasks.php">My Tasks</a>
            <a href="<?php echo $base; ?>task_form.php">Add Task</a>
            <a href="<?php echo $base; ?>progress_page.php">Progress</a>
            <a href="<?php echo $base; ?>my_courses.php">My Courses</a>
            <a href="<?php echo $base; ?>courses_available.php">Find Courses</a>
        <?php else: ?>
            <a href="<?php echo $base; ?>index.php">Dashboard</a>
            <a href="<?php echo $base; ?>i_courses.php">Courses</a>
            <a href="<?php echo $base; ?>i_assignments.php">Assignments</a>
            <a href="<?php echo $base; ?>i_assignments_form.php">New Assignment</a>
            <a href="<?php echo $base; ?>i_progress.php">Student Progress</a>
        <?php endif; ?>
        <?php if ($user): ?>
            <a href="<?php echo $base; ?>profile.php"><?php echo e($user['username']); ?></a>
            <form class="nav-form" action="<?php echo $base; ?>logout.php" method="post">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-button">Log out</button>
            </form>
        <?php endif; ?>
    </nav>
</header>

