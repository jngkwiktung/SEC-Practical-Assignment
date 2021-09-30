<?php
    const USER_SESSION_KEY = 'user'; // Stores user logged-in object
    const PRIVATE_KEY = 'private_key';
    const PUBLIC_KEY = 'public_key';
    const CLIENT_PUBLIC_KEY = 'client_public_key';
    const DES_SESSION_KEY = 'des_session_key';
    session_start();


    function isUserLoggedIn() {
        return isset($_SESSION[USER_SESSION_KEY]);
    }

    function getLoggedInUser() {
        return isUserLoggedIn() ? $_SESSION[USER_SESSION_KEY] : null;
    }

    function displayError($text) {
        if(isset($text))
            return "<div class='error'>$text</div>";
    }

    function logoutUser() {
        session_unset();
    }

    function generatePrivatePublicKey() {

        // Create the private and public key
        $res = openssl_pkey_new();
        // Extract the private key
        openssl_pkey_export($res, $_SESSION[PRIVATE_KEY]);
        // Extract the public key
        $_SESSION[PUBLIC_KEY] = openssl_pkey_get_details($res)['key'];

    }

    function getPublicKey() {
        return isset($_SESSION[PUBLIC_KEY]) ? $_SESSION[PUBLIC_KEY] : null;
    }

    function encryptData($data, $publicKey) {
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return base64_encode($encrypted);
    }
?>