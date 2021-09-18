<?php require_once('includes/authorise.inc.php'); ?>
<?php require_once('includes/functions.inc.php'); ?>
<?php require_once('model/user.php'); ?>
<?php require_once('model/product.php'); ?>
<?php require_once('model/shoppingCart.php'); ?>
<!doctype html>
<html>

<head>
    <title>Main Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/main.css">
</head>

<body>
    Main/Catalog Page
    <form id="logoutForm" action="/SEC-Practical-Assignment/logout.php" method="post"> 
        <input name="logout" id="logout" type="submit" value="Logout">
    </form>
</body>

</html>