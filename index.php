<?php require_once('includes/authorise.inc.php'); ?>
<?php require_once('includes/functions.inc.php'); ?>
<?php require_once('model/user.php'); ?>
<?php require_once('model/product.php'); ?>
<?php require_once('model/shoppingCart.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Catalog | Ecommerce Website Design</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" href="images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>

    <!--Header-->
    <div class="header">
        <div class="container">
            <div class="navbar">
                <!--Logo on the left side-->
                <div class="logo">
                    <img src="images/app_icon.png" width="125px">
                </div>

                <!--navbar links-->
                <nav>
                    <ul>
                        <li><a href="">Home</a></li>
                        <li><a href="">Products</a></li>
                        <li><a href="">About</a></li>
                        <li><a href="">Contact</a></li>
                        <li><a href="">Account</a></li>
                        <li><a href="">Logout</a></li>
                    </ul>
                </nav>

                <!--Cart image-->
                <img src="images/cart.png" width="30px" height="30px">

            </div>
        </div>
    </div>

    <form id="logoutForm" action="/SEC-Practical-Assignment/logout.php" method="post">
        <input name="logout" id="logout" type="submit" value="Logout">
    </form>

    <!------- featured PRODUCTS -------->
    <div class="small-container">
        <h2 class="title">Featured Products</h2>
        <div class="row">
            <div class="prod-col">
                <img src="images/fiji_natural_artesian_water.PNG">
                <h4>Natural Fiji Water</h4>
                <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                </div>
                <p>$5.00</p>
            </div>
            <div class="prod-col">
                <img src="images/horizon_forbidden_west_ps5.PNG">
                <h4>Horizon Forbidden West (PS5)</h4>
                <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <p>$109.00</p>
            </div>
            <div class="prod-col">
                <img src="images/iphone_13_pro_max.PNG" alt="">
                <h4>Iphone 13 Pro Max</h4>
                <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-half-o"></i>
                </div>
                <p>$1849.00</p>
            </div>
            <div class="prod-col">
                <img src="images/koala_lounging_sofa.PNG">
                <h4>Koala Lounging Sofa</h4>
                <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                </div>
                <p>$899.00</p>
            </div>
            <div class="prod-col">
                <img src="images/oztrail_gazebo.PNG">
                <h4>Oztrail Gazebo</h4>
                <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                </div>

                <p>$88.00</p>
            </div>
        </div>
    </div>



</body>

</html>