<?php
    require_once('includes/functions.inc.php');
    if (isset($_POST['publicKeyClient'])) {
        $_SESSION[CLIENT_PUBLIC_KEY] = $_POST['publicKeyClient'];
    }
?>