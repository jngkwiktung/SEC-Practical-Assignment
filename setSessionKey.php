<?php
    require_once('includes/functions.inc.php');
    if (isset($_POST['desKeyClient'])) {
        openssl_private_decrypt(base64_decode($_POST['desKeyClient']), $decryptedKey, $_SESSION[PRIVATE_KEY]);
        $_SESSION[DES_SESSION_KEY] = $decryptedKey;
    }
?>