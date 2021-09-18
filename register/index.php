<?php require_once('../crud/userCrud.php'); ?>
<?php require_once('../includes/functions.inc.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php
    $usernameExistsError = false;
    $usernameLengthError = false;
    $usernameNull = false;
    $passwordNull = false;
    if(isset($_POST['register'])) {
        $userCrud = new UserCrud();
        if ($_POST['username'] == '') {
            $usernameNull = true;
        }
        if ($_POST['pw'] == '') {
            $passwordNull = true;
        }

        if (!$usernameNull && !$passwordNull) {
            if (strlen($_POST['username']) <= 20) {
                if (!$userCrud->usernameExists(htmlspecialchars(trim($_POST['username'])))) {
                    $randomNumber = rand();
                    $currentUser = new User($randomNumber, htmlspecialchars(trim($_POST['username'])), hash("sha256", htmlspecialchars(trim($_POST['pw'])), false));
                    $userCrud->create($currentUser);
                    $_SESSION[USER_SESSION_KEY] = $currentUser;
                    header('Location: /SEC-Practical-Assignment/');
                    exit();
                } else {
                    $usernameExistsError = true;
                }
            } else {
                $usernameLengthError = true;
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
                        <input type="text" id="username" name="username" 
                        <?php 
                            if (isset($_POST['register'])) {
                                echo 'value="'.$_POST['username'].'"';
                            } else {
                                echo 'value=""';
                            }
                        ?>
                        required maxlength="20">
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
                        <input type="password" id="pw" name="pw" 
                        <?php 
                            if (isset($_POST['register'])) {
                                echo 'value="'.$_POST['pw'].'"';
                            } else {
                                echo 'value=""';
                            }
                        ?>
                        required>
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
                    <input name="register" id="register" type="submit" value="Register">
                </div>
            </form>
        </div>
    </article>
    <footer class="noselect">
        <p>&copy; 2021 Jeremy Ng Kwik Tung, Steven Harja, Wilbert Ongosari & Aman Khan</p>
    </footer>
</body>

</html>