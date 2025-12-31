<?php
session_start();


unset($_SESSION['adminUserId']); 
unset($_SESSION['adminfirstname']); 
unset($_SESSION['adminrole']); 


header("Location: index.php");
exit();

