<?php
include 'db.php';
include 'auth.php';
if (current_user()) { redirect('index.php'); }
include 'header.php';
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>Log in</h2>

<form class="form-card" action="h_login.php" method="post">
    <?php echo csrf_field(); ?>

    <label for="username">Username</label>
    <input type="text" id="username" name="username" maxlength="40" value="<?php echo old('username'); ?>" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" class="button" value="Log in">
    <p class="muted">New here? <a href="register.php">Create an account</a></p>
</form>
</main>

<?php
mysqli_close($conn);
