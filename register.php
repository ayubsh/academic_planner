<?php
include 'db.php';
include 'auth.php';
if (current_user()) { redirect('index.php'); }
include 'header.php';
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Create an account</h2>

<form class="form-card" action="h_register.php" method="post">
    <?php echo csrf_field(); ?>

    <label for="username">Username</label>
    <input type="text" id="username" name="username" maxlength="40" value="<?php echo old('username'); ?>" required>

    <label for="full_name">Full name</label>
    <input type="text" id="full_name" name="full_name" maxlength="80" value="<?php echo old('full_name'); ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" maxlength="100" value="<?php echo old('email'); ?>" required>

    <label for="password">Password (8+ characters)</label>
    <input type="password" id="password" name="password" minlength="8" required>

    <label for="confirm">Confirm password</label>
    <input type="password" id="confirm" name="confirm" minlength="8" required>

    <label for="role">I am a</label>
    <select id="role" name="role" required>
        <option value="student"<?php echo selected($_SESSION['old']['role'] ?? 'student', 'student'); ?>>Student</option>
        <option value="instructor"<?php echo selected($_SESSION['old']['role'] ?? '', 'instructor'); ?>>Instructor</option>
    </select>

    <input type="submit" class="button" value="Register">
    <p class="muted">Already have an account? <a href="login.php">Log in</a></p>
</form>
</main>

<?php
mysqli_close($conn);
