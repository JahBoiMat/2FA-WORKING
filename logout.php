<?php
session_start();
unset($_SESSION['authenticated']);
unset($_SESSION['auth_user']);
$_SESSION['status'] = "You have been Logged Out";
header("Location: login.php");
exit(0);

?>