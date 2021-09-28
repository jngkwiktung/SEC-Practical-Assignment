<?php require_once('../crud/userCrud.php'); ?>
<?php require_once('../includes/functions.inc.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php
    if (!isset($_SESSION[PRIVATE_KEY]) && !isset($_SESSION[PUBLIC_KEY])) {
        generatePrivatePublicKey();
    }
    $usernameExistsError = false;
    $usernameLengthError = false;
    $usernameNull = false;
    $passwordNull = false;
    if(isset($_POST['register'])) {
        $userCrud = new UserCrud();
        openssl_private_decrypt(base64_decode($_POST['username']), $decryptedUsername, $_SESSION[PRIVATE_KEY]);
        openssl_private_decrypt(base64_decode($_POST['pw']), $decryptedPassword, $_SESSION[PRIVATE_KEY]);
        $passwordSplit = explode("&&&&&", $decryptedPassword);
        $userTimestamp = $passwordSplit[count($passwordSplit)-1];
        $serverTimeStamp = time();
        if ($decryptedUsername == '') {
            $usernameNull = true;
        }
        if ($passwordSplit[0] == '') {
            $passwordNull = true;
        }
        if(abs($serverTimeStamp - $userTimestamp) >= 150) {
            echo("<p>Your session has expired</p>");
            exit();
        } else {
            if (!$usernameNull && !$passwordNull) {
                if (strlen($decryptedUsername) <= 20) {
                    if (!$userCrud->usernameExists(htmlspecialchars(trim($decryptedUsername)))) {
                        $randomNumber = rand();
                        $currentUser = new User($randomNumber, htmlspecialchars(trim($decryptedUsername)), $passwordSplit[0], false);
                        $userCrud->create($currentUser);
                        $_SESSION[USER_SESSION_KEY] = $currentUser;
                        header('Location: ../');
                        exit();
                    } else {
                        $usernameExistsError = true;
                    }
                } else {
                    $usernameLengthError = true;
                }
            }
        }
        
    }
?>

<!doctype html>
<html>

<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/register.css">
    <script type="text/javascript" src="../scripts/sha256.js"></script>
    <script type="text/javascript" src="../scripts/rsa.js"></script>
    <script type="text/javascript" src="../scripts/functions.js"></script>
</head>

<body>
    <header>
        <br>
        <h1 class="noselect"><b>Register</b></h1>
    </header>
    <article>
        <div class="formcontainer">
            <form id="registerForm" method="post">
                <div class="aftercol">
                    <div class="firstcol noselect">
                        <label for="username">Username</label>
                    </div>
                    <div class="secondcol">
                        <input type="text" id="username" name="username" required maxlength="20">
                    </div>
                    <?php 
                        if ($usernameExistsError) {
                            echo displayError("Username already taken!"); 
                        } else if ($usernameLengthError) {
                            echo displayError("Username is too long!"); 
                        } else if ($usernameNull) {
                            echo displayError("Username field is empty!"); 
                        }
                    ?>
                </div>

                <div class="aftercol">
                    <div class="firstcol noselect">
                        <label for="pw">Password</label>
                    </div>

                    <div class="secondcol">
                        <input type="password" id="pw" name="pw" required>
                    </div>
                    <?php 
                        if ($passwordNull) {
                            echo displayError("Password field is empty!"); 
                        }
                    ?>
                </div>

                <div class="aftercol">
                    <a href="../login/">
                        <p>Cancel</p>
                    </a>
                    <input name="register" id="register" type="submit" value="Register" onclick="encryptUsnPass(`<?php echo getPublicKey();?>`)">
                </div>
            </form>
        </div>
    </article>
    <footer class="noselect">
        <p>&copy; 2021 Jeremy Ng Kwik Tung, Steven Harja, Wilbert Ongosari & Aman Khan</p>
    </footer>
</body>

</html>