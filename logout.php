<?php
    require_once('includes/functions.inc.php');

    logoutUser();
    header('Location: login/');
    exit();
?>