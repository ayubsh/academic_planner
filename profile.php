<?php
include 'db.php';
include 'auth.php';
require_login();
include 'header.php';

$me = db_one($conn, "SELECT username, full_name, email, role FROM users WHERE id = ?", "i", [current_user()['id']]);
?>
<link rel="stylesheet" href="style.css">
<main class="container">
<h2>My Profile</h2>

<form class="form-card" action="handlers/update_profile.php" method="post">
    <?php echo csrf_field(); ?>
    <p class="muted">Username: <strong><?php echo e($me['username']); ?></strong> (<?php echo e($me['role']); ?>)</p>

    <label for="full_name">Full name</label>
    <input type="text" id="full_name" name="full_name" maxlength="80" value="<?php echo old('full_name', $me['full_name']); ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" maxlength="100" value="<?php echo old('email', $me['email']); ?>" required>

    <input type="submit" class="button" value="Save profile">
</form>

<h3>Change password</h3>
<form class="form-card" action="handlers/change_password.php" method="post">
    <?php echo csrf_field(); ?>

    <label for="current">Current password</label>
    <input type="password" id="current" name="current" required>

    <label for="new_password">New password (8+ characters)</label>
    <input type="password" id="new_password" name="new_password" minlength="8" required>

    <label for="confirm">Confirm new password</label>
    <input type="password" id="confirm" name="confirm" minlength="8" required>

    <input type="submit" class="button" value="Change password">
</form>
</main>

<?php
mysqli_close($conn);
