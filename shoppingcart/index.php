<?php require_once('../crud/shoppingCartCrud.php'); ?>
<?php require_once('../crud/cartItemCrud.php'); ?>
<?php require_once('../crud/productCrud.php'); ?>
<?php require_once('../includes/authorise.inc.php'); ?>
<?php require_once('../model/shoppingCart.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php require_once('../model/cartItem.php'); ?>
<?php require_once('../model/product.php'); ?>
<!doctype html>
<html>

<head>
    <title>Shopping Cart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/shoppingcart.css">
</head>

<body>
    Shopping Cart Page
    <form id="logoutForm" action="/SEC-Practical-Assignment/logout.php" method="post"> 
        <input name="logout" id="logout" type="submit" value="Logout">
    </form>
</body>

</html>