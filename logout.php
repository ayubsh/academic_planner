<?php
include 'auth.php';
require_post('index.php');

$_SESSION = [];
session_regenerate_id(true);
set_flash("You have been logged out.");
redirect('login.php');
