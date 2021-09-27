<?php
    require_once('functions.inc.php');

    if(!isUserLoggedIn()) {
        header('Location: ../SEC-Practical-Assignment/login/');
        exit();
    }
?>