<?php require_once('../crud/orderCrud.php'); ?>
<?php require_once('../includes/authorise.inc.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php require_once('../model/order.php'); ?>
<!doctype html>
<html>

<head>
    <title>Order Summary</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/order.css">
</head>

<body>
    Order Summary Page
    <form id="logoutForm" action="/SEC-Practical-Assignment/logout.php" method="post"> 
        <input name="logout" id="logout" type="submit" value="Logout">
    </form>
</body>

</html>