<?php require_once('model/user.php'); ?>
<?php require_once('model/product.php'); ?>
<?php require_once('model/shoppingCart.php'); ?>
<?php require_once('includes/authorise.inc.php'); ?>
<?php require_once('includes/functions.inc.php'); ?>
<?php require_once('crud/productCrud.php'); ?>
<?php
    // $_SESSION[DES_SESSION_KEY] = 'kjeRweiuhTawehweFLiawEh';
    if (isset($_SESSION[DES_SESSION_KEY])) {
        $currentUser = getLoggedInUser();
        $productCrud = new ProductCrud();
        $products = $productCrud->readAll();
        $encryptedUsername = encryptData($currentUser->getUsername(), $_SESSION[CLIENT_PUBLIC_KEY]);
        $encryptedSessionKey = encryptData($_SESSION[DES_SESSION_KEY], $_SESSION[CLIENT_PUBLIC_KEY]);
    } else {
        header('Location: logout.php');
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Catalog | Ecommerce Website Design</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" href="images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="scripts/sha256.js"></script>
    <script type="text/javascript" src="scripts/rsa.js"></script>
    <script type="text/javascript" src="scripts/functions.js"></script>

</head>

<body onload="checkSessionKey(`<?php echo $encryptedSessionKey; ?>`)">
    <?php
        $results = "<script>document.write(results)</script>"
    ?>

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
                        <li>Welcome '<span id="usernameHome"></span>'!</li>
                        <li><a href="">Home</a></li>
                        <li><a href="">Account</a></li>
                        <li>
                            <form id="logoutForm" action="logout.php" method="post">
                                <input name="logout" id="logout" type="submit" value="Logout" onclick="removePrivatePublicKey()">
                            </form>
                        </li>
                    </ul>
                </nav>

                <!--Cart image-->
                <a href="shoppingcart/">
                    <img src="images/cart.png" width="30px" height="30px">
                </a>

            </div>
        </div>
    </div>


    <!------- featured PRODUCTS -------->
    <div class="small-container">
        <h2 class="title">Featured Products</h2>
        <div class="row">
            <?php $i = 0; ?>
            <?php foreach ($products as $product):?>
                <?php
                    $encryptedProductName = encryptData($product->getName(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductPrice = encryptData($product->getPrice(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductImage = encryptData($product->getImage(), $_SESSION[CLIENT_PUBLIC_KEY]);
                ?>
                <div class="prod-col">
                    <a id="product<?php echo $i; ?>" href="">
                        <img id="productImage<?php echo $i; ?>" src="">
                        <h4 id="productName<?php echo $i; ?>"></h4>
                    </a>
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <p id="productPrice<?php echo $i; ?>"></p>
                </div>
                <script>
                    encryptProductLink("product<?php echo $i; ?>", "<?php echo $product->getProductId(); ?>", `<?php echo getPublicKey();?>`);
                    decryptImage('productImage<?php echo $i; ?>', '<?php echo $encryptedProductImage; ?>', 'images/');
                    decryptData('productName<?php echo $i; ?>', '<?php echo $encryptedProductName; ?>', '');
                    decryptData('productPrice<?php echo $i; ?>', '<?php echo $encryptedProductPrice; ?>', '$');
                </script>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    </div>



</body>
<script>
    decryptData('usernameHome', '<?php echo $encryptedUsername; ?>', '');
</script>
</html>