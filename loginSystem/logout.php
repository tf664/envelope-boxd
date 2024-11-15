<?php
session_start();
session_destroy();
header("Location: loginSystem/login.php");
exit();
?>