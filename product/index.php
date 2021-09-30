<?php require_once('../model/user.php'); ?>
<?php require_once('../model/product.php'); ?>
<?php require_once('../model/shoppingCart.php'); ?>
<?php require_once('../includes/authorise.inc.php'); ?>
<?php require_once('../includes/functions.inc.php'); ?>
<?php require_once('../crud/productCrud.php'); ?>
<?php require_once('../crud/shoppingCartCrud.php'); ?>
<?php require_once('../crud/cartItemCrud.php'); ?>
<?php require_once('../crud/orderCrud.php'); ?>
<?php
    if (isset($_SESSION[DES_SESSION_KEY]))  {
        $encryptedSessionKey = encryptData($_SESSION[DES_SESSION_KEY], $_SESSION[CLIENT_PUBLIC_KEY]);
        $currentUser = getLoggedInUser();
        $productCrud = new ProductCrud();
        $shoppingCartCrud = new ShoppingCartCrud();
        $cartItemCrud = new CartItemCrud();
        $orderCrud = new OrderCrud();
    
    
        openssl_private_decrypt(base64_decode(str_replace(' ', '+', $_GET['product'])), $decryptedProduct, $_SESSION[PRIVATE_KEY]);
        $product = $productCrud->getProductById($decryptedProduct);
    
        $currentCart = null;
        $inCart = false;
        $encryptedUsername = encryptData($currentUser->getUsername(), $_SESSION[CLIENT_PUBLIC_KEY]);
        
        if (isset($product)) {
            $encryptedProductName = encryptData($product->getName(), $_SESSION[CLIENT_PUBLIC_KEY]);
            $encryptedProductManufacturer = encryptData($product->getManufacturer(), $_SESSION[CLIENT_PUBLIC_KEY]);
            $encryptedProductPrice = encryptData($product->getPrice(), $_SESSION[CLIENT_PUBLIC_KEY]);
            $encryptedProductImage = encryptData($product->getImage(), $_SESSION[CLIENT_PUBLIC_KEY]);
            $encryptedProductCategory = encryptData($product->getCategory(), $_SESSION[CLIENT_PUBLIC_KEY]);
    
            $allCartsOfUser = $shoppingCartCrud->getCartsByUserId($currentUser->getUserId()); // Get all carts of current user
        
            // Look for carts that is available, basically looking for a cart that does not have their transaction done yet/or in the order table
            foreach($allCartsOfUser as $cart) {
                if ($orderCrud->getOrderByCartId($cart->getCartId()) == null) {
                    $currentCart = $cart; // Assign 'current shopping cart' variable a shopping cart object
                } 
            }
            // Now to look if this item is in the current shopping cart (CartItem table)
            if (isset($currentCart) && $cartItemCrud->getOneCartItem($product->getProductId(), $currentCart->getCartId()) != null) {
                $inCart = true;
            }
        }
    
    
    
        $quantityError = false;
        // For Post form...
        if (isset($_POST['quantity']) && !$inCart) {
            openssl_private_decrypt(base64_decode($_POST['quantity']), $decryptedQuantity, $_SESSION[PRIVATE_KEY]);
            $quantitySplit = explode("&&&&&", $decryptedQuantity);
            $userTimestamp = $quantitySplit[count($quantitySplit)-1];
            $serverTimeStamp = time();
            if(abs($serverTimeStamp - $userTimestamp) >= 150) {
                echo("<p>Your session has expired</p>");
                exit();
            } else {
                if (is_numeric($quantitySplit[0])) {
                    if (isset($currentCart)) {
                        $cartItemCrud->create(new CartItem($product->getProductId(), $currentCart->getCartId(), $quantitySplit[0]));
                    } else {
                        $randomNumber = rand();
                        $shoppingCartCrud->create(new ShoppingCart($randomNumber, $currentUser->getUserId()));
                        $cartItemCrud->create(new CartItem($product->getProductId(), $randomNumber, $quantitySplit[0]));
                    }
                    $inCart = true;
    
                } else {
                    $quantityError = true;
                }
            }
        }
    } else {
        header('Location: ../logout.php');
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title id="title">Error | Ecommerce Website Design</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" href="../images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/product.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="../scripts/sha256.js"></script>
    <script type="text/javascript" src="../scripts/rsa.js"></script>
    <script type="text/javascript" src="../scripts/functions.js"></script>

</head>

<body onload="checkSessionKey(`<?php echo $encryptedSessionKey; ?>`)">
    <!--Header-->
    <div class="header">
        <div class="container">
            <div class="navbar">
                <!--Logo on the left side-->
                <div class="logo">
                    <a href="../">
                        <img src="../images/app_icon.png" width="125px">
                    </a>
                </div>
                
                <!--navbar links-->
                <nav>
                    <ul>
                        <li>Welcome '<span id="usernameProduct"></span>'!</li>
                        <li><a href="../">Home</a></li>
                        <li><a href="">Account</a></li>
                        <li>
                            <form id="logoutForm" action="../logout.php" method="post">
                                <input name="logout" id="logout" type="submit" value="Logout" onclick="removePrivatePublicKey()">
                            </form>
                        </li>
                    </ul>
                </nav>

                <!--Cart image-->
                <a href="../shoppingcart/">
                    <img src="../images/cart.png" width="30px" height="30px">
                </a>
            </div>
        </div>
    </div>
    <br>
    <?php if (isset($product)) { ?>
        <div class="grid-container">
            <div class="item1 aligncenter">
                <img id="productImage" src="" width="100%">
            </div>  
            <div class="item2">
                <h3 id="productName"></h3>
                <h4 id="productManufacturer"></h4>
                <hr>
                <h6 id="productPrice"></h6>
                <?php if (!$inCart) { ?>
                    <form id="addItemForm" method="post">
                        <div class="quantity">Quantity:</div>
                        <input type="text" id="quantity" name="quantity" required placeholder="Enter a number..." min="1" max="50"><br>
                        <?php 
                            if ($quantityError) {
                                echo displayError("Quantity incorrect! Please enter a number."); 
                            }
                        ?>
                        <button name="addItem" id="addItem" value="Add to Cart" onclick="encryptSendQuantity(`<?php echo getPublicKey();?>`)">Add to Cart</button>
                    </form>
                <?php } else { ?>
                    <div class="quantity">Item in cart</div>
                <?php } ?>
            </div>
            <div class="item3">
                <p id="productCategory" class="category"></p>
                <hr>
                <p class="description"><?php echo $product->getDescription(); ?></p>
            </div>
        </div>
    <?php } else { ?>
        <div class="aligncenter">Error: Product does not exist</div>
    <?php } ?>
    <br>
</body>
<script>
    
    decryptData('usernameProduct', '<?php echo $encryptedUsername; ?>', '');
    <?php if (isset($product)) { ?>
        decryptData('productName', '<?php echo $encryptedProductName; ?>', '');
        decryptData('title', '<?php echo $encryptedProductName; ?>', '');
        document.getElementById('title').innerHTML += " | Ecommerce Website Design";
        decryptImage('productImage', '<?php echo $encryptedProductImage; ?>', '../images/');
        decryptData('productManufacturer', '<?php echo $encryptedProductManufacturer; ?>', 'Manufacturer: ');
        decryptData('productPrice', '<?php echo $encryptedProductPrice; ?>', '$');
        decryptData('productCategory', '<?php echo $encryptedProductCategory; ?>', 'Category: ');
    <?php } else { ?>
        document.getElementById('title').innerHTML = "Error";
    <?php } ?>
    
</script>
</html>