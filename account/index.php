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
</body>

</html>