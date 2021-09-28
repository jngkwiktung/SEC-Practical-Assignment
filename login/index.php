<?php require_once('../crud/userCrud.php'); ?>
<?php require_once('../includes/functions.inc.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php
    if (!isset($_SESSION[PRIVATE_KEY]) && !isset($_SESSION[PUBLIC_KEY])) {
        generatePrivatePublicKey();
    }
    $loginError = false;
    if(isset($_POST['login'])) {
        $userCrud = new UserCrud();
        openssl_private_decrypt(base64_decode($_POST['username']), $decryptedUsername, $_SESSION[PRIVATE_KEY]);
        openssl_private_decrypt(base64_decode($_POST['pw']), $decryptedPassword, $_SESSION[PRIVATE_KEY]);
        $passwordSplit = explode("&&&&&", $decryptedPassword);
        $userTimestamp = $passwordSplit[count($passwordSplit)-1];
        $serverTimeStamp = time();
        if(abs($serverTimeStamp - $userTimestamp) >= 150) {
            echo("<p>Your session has expired</p>");
            exit();
        } else {
            $currentUser = $userCrud->isLogin($decryptedUsername, $passwordSplit[0]);
            if (isset($currentUser)) {
                $_SESSION[USER_SESSION_KEY] = $currentUser;
                header('Location: ../');
                exit();
            } else {
                $loginError = true;
            }
        }
    }
?>
<!doctype html>
<html>

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/login.css">
    <script type="text/javascript" src="../scripts/sha256.js"></script>
    <script type="text/javascript" src="../scripts/rsa.js"></script>
    <script type="text/javascript" src="../scripts/functions.js"></script>
</head>

<body>
    <header>
        <br>
        <h1 class="noselect"><b>Login</b></h1>
    </header>
    <article>
        <div class="formcontainer">
            <form id="loginForm" method="post">
                <div class="aftercol">
                    <div class="firstcol noselect">
                        <label for="username">Username</label>
                    </div>
                    <div class="secondcol">
                        <input type="text" id="username" name="username" required>
                    </div>
                    <?php 
                        if ($loginError) {
                            echo displayError("Username incorrect!"); 
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
                        if ($loginError) {
                            echo displayError("Password incorrect!"); 
                        }
                    ?>
                </div>

                <div class="aftercol">
                    <a href="../register/">
                        <p>Don't have an account? Click here!</p>
                    </a>
                    <input id="login" name="login" type="submit" value="Login" onclick="encryptUsnPass(`<?php echo getPublicKey();?>`)">
                </div>
            </form>
        </div>
    </article>
    <footer class="noselect">
        <p>&copy; 2021 Jeremy Ng Kwik Tung, Steven Harja, Wilbert Ongosari & Aman Khan</p>
    </footer>
</body>

</html>