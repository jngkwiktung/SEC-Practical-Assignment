<?php require_once('../crud/userCrud.php'); ?>
<?php require_once('../includes/authorise.inc.php'); ?>
<?php require_once('../includes/functions.inc.php'); ?>
<?php require_once('../model/user.php'); ?>

<?php
    // $_SESSION[DES_SESSION_KEY] = 'kjeRweiuhTawehweFLiawEh';
    if (isset($_SESSION[DES_SESSION_KEY])) {
        $currentUser = getLoggedInUser();
        $encryptedUsername = encryptData($currentUser->getUsername(), $_SESSION[CLIENT_PUBLIC_KEY]);
        $encryptedSessionKey = encryptData($_SESSION[DES_SESSION_KEY], $_SESSION[CLIENT_PUBLIC_KEY]);
        //print_r($currentUser);
    } else {
        header('Location: logout.php');
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Account | Ecommerce Website Design</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="../styles/account.css">
</head>

<body>
    <!--Header-->
    <div class="header">
        <div class="container">
            <div class="navbar">
                <!--Logo on the left side-->
                <div class="logo">
                    <img src="../images/app_icon.png" width="125px">
                </div>

                <!--navbar links-->
                <nav>
                    <ul>
                        <li><a href="../">Home</a></li>
                        <li>
                            <form id="logoutForm" action="logout.php" method="post">
                                <input name="logout" id="logout" type="submit" value="Logout"
                                    onclick="removePrivatePublicKey()">
                            </form>
                        </li>
                    </ul>
                </nav>

                <!--Cart image-->
                <a href="shoppingcart/">
                    <img src="../images/cart.png" width="30px" height="30px">
                </a>

            </div>
        </div>
    </div>

    <div class="acc-container">
        <div class="small-container">
            <form action="index.php" method="POST">
                <fieldset class="fieldset">
                    <h2>Your Account Details</h2> <br>

                    <h3><b>Username:</b></h3>
                    <?php echo $currentUser->getUsername()?>
                    <!--<input type="text" name="username"> <br>-->

                    <br><br>
                    <h3><b>Password:</b></h3>
                    <input type="password" id="password" name="password"> <br>

                    <a href="">
                        <p>Change Password?</p>
                    </a> <br>

                    <h3><b>Firstname:</b></h3>
                    <input type="text" name="fname"> <br><br>

                    <h3><b>Lastname:</b></h3>
                    <input type="text" name="lname"> <br><br>

                    <h3><b>Date of Birth:</b></h3>
                    <input type="text" name="dob">
                    <br><br>
                    <h3><b>Shipping address:</b></h3>
                    <input type="text" name="shipAddress"> <br><br>

                    <input type="submit" value="Update Details">
                </fieldset>
            </form>
        </div>
    </div>

</body>

</html>