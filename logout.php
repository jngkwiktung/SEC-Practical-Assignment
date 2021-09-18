<?php
    require_once('includes/functions.inc.php');

    logoutUser();
    header('Location: /SEC-Practical-Assignment/login/');
    exit();
?>