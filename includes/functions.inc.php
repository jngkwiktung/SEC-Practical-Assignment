<?php
    const USER_SESSION_KEY = 'user'; // Stores user logged-in object
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
?>