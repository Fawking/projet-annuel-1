<?php
ob_start();
session_start();
require_once $_SERVER['DOCUMENT_ROOT']."/config.php";

session_unset();
session_destroy(); 
header("Location: /");
exit();
?>