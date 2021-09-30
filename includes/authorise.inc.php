<?php
    require_once('functions.inc.php');

    if(!isUserLoggedIn()) {
        if (file_exists(('../login/index.php'))) {
            header('Location: ../login/');
        } else {
            header('Location: login/');
        }
        exit();
    }
?>