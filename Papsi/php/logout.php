<?php
//Ateratzeko, ez dago misteriorik 
session_start();
session_destroy();
header("Location: ../login.php"); 
exit;
?>