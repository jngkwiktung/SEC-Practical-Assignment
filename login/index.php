<?php require_once('../crud/userCrud.php'); ?>
<?php require_once('../includes/functions.inc.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php
    $loginError = false;
    if(isset($_POST['login'])) {
        $userCrud = new UserCrud();
        $currentUser = $userCrud->isLogin($_POST['username'], hash("sha256", $_POST['pw'], false));
        if (isset($currentUser)) {
            $_SESSION[USER_SESSION_KEY] = $currentUser;
            header('Location: /SEC-Practical-Assignment/');
            exit();
        } else {
            $loginError = true;
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
                        <input type="text" id="username" name="username" 
                        <?php 
                            if (isset($_POST['login'])) {
                                echo 'value="'.$_POST['username'].'"';
                            } else {
                                echo 'value=""';
                            }
                        ?>
                        required>
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
                        <input type="password" id="pw" name="pw" 
                        <?php 
                            if (isset($_POST['login'])) {
                                echo 'value="'.$_POST['pw'].'"';
                            } else {
                                echo 'value=""';
                            }
                        ?>
                        required>
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
                    <input id="login" name="login" type="submit" value="Login">
                </div>
            </form>
        </div>
    </article>
    <footer class="noselect">
        <p>&copy; 2021 Jeremy Ng Kwik Tung, Steven Harja, Wilbert Ongosari & Aman Khan</p>
    </footer>
</body>

</html>