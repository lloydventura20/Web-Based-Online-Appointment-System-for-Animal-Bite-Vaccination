<?php
session_start();


unset($_SESSION['userid']); 
unset($_SESSION['firstname']); 



header("Location: adminlogin.php");
exit();
