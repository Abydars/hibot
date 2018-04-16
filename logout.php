<?php
require_once('config.php');
unset($_SESSION['user_id']);
session_destroy();

header("location: page-login.php");
?>
