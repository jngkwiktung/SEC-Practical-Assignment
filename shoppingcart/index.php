<?php require_once('../model/shoppingCart.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php require_once('../model/cartItem.php'); ?>
<?php require_once('../model/product.php'); ?>
<?php require_once('../includes/authorise.inc.php'); ?>
<?php require_once('../includes/functions.inc.php'); ?>
<?php require_once('../crud/productCrud.php'); ?>
<?php require_once('../crud/shoppingCartCrud.php'); ?>
<?php require_once('../crud/cartItemCrud.php'); ?>
<?php require_once('../crud/orderCrud.php'); ?>
<?php
    if (isset($_SESSION[DES_SESSION_KEY])) {
        $currentUser = getLoggedInUser();
        $productCrud = new ProductCrud();
        $shoppingCartCrud = new ShoppingCartCrud();
        $cartItemCrud = new CartItemCrud();
        $orderCrud = new OrderCrud();
        // $products = $productCrud->readAll();
        $encryptedUsername = encryptData($currentUser->getUsername(), $_SESSION[CLIENT_PUBLIC_KEY]);
        $encryptedSessionKey = encryptData($_SESSION[DES_SESSION_KEY], $_SESSION[CLIENT_PUBLIC_KEY]);
        $quantityError = false;
        $addressError = false;
        $creditCardError = false;
        if(isset($_POST['emptcart'])) {
            if ($_POST['emptyCartInput'] != '') {
                openssl_private_decrypt(base64_decode($_POST['emptyCartInput']), $decrypted, $_SESSION[PRIVATE_KEY]);
                $decryptedSplit = explode("&&&&&", $decrypted);
                $userTimestamp = $decryptedSplit[count($decryptedSplit)-1];
                $serverTimeStamp = time();
                if(abs($serverTimeStamp - $userTimestamp) >= 150) {
                    echo("<p>Your session has expired</p>");
                    exit();
                } else {
                    $cartItems = $cartItemCrud->getAllCartItemFromCartId($decryptedSplit[0]);
                    foreach ($cartItems as $productId => $cartItem) {
                        $cartItemCrud->delete($cartItem);
                    }
                    $shoppingCartCrud->delete($shoppingCartCrud->getCartById($decryptedSplit[0]));
                }
            }
            } else if (isset($_POST['placeorder'])) {
            openssl_private_decrypt(base64_decode($_POST['placeOrderInput']), $decrypted, $_SESSION[PRIVATE_KEY]);
            $decryptedSplit = explode("&&&&&", $decrypted);
            $userTimestamp = $decryptedSplit[1];
            $serverTimeStamp = time();
            if(abs($serverTimeStamp - $userTimestamp) >= 150) {
                echo("<p>Your session has expired</p>");
                exit();
            } else {

                if ($decryptedSplit[2] == '') {
                    $addressError = true;
                }
                if ($decryptedSplit[3] == '' || !is_numeric($decryptedSplit[3])) {
                    $creditCardError = true;
                }

                // To update cart item quantity first
                for ($i = 5; $i < count($decryptedSplit); $i+=2) {
                    // echo $decryptedSplit[$i+1];
                    if ($decryptedSplit[$i+1] == '' || !is_numeric($decryptedSplit[$i+1])) {
                        $quantityError = true;
                    } else {
                        $cartItemModel = new CartItem($decryptedSplit[$i], $decryptedSplit[0], $decryptedSplit[$i+1]);
                        $cartItemCrud->update($cartItemModel);
                    }
                }

                if (!$quantityError && !$addressError && !$creditCardError) {
                    // Create a new row at the order table
                    $randomNumber = rand();
                    $orderModel = new Order($randomNumber, $decryptedSplit[0], $decryptedSplit[4], $decryptedSplit[2], $decryptedSplit[3], $serverTimeStamp);
                    $orderCrud->create($orderModel);
                    $encryptedOrderId = encryptData($randomNumber, $_SESSION[PUBLIC_KEY]);
                    header('Location: ../order/?order_id=' . $encryptedOrderId);
                    exit();
                }
            }
        }

        $currentCart = null;
        $allCartsOfUser = $shoppingCartCrud->getCartsByUserId($currentUser->getUserId()); // Get all carts of current user
        
        // Look for carts that is available, basically looking for a cart that does not have their transaction done yet/or in the order table
        foreach($allCartsOfUser as $cart) {
            if ($orderCrud->getOrderByCartId($cart->getCartId()) == null) {
                $currentCart = $cart; // Assign 'current shopping cart' variable a shopping cart object
            } 
        }
        if (isset($currentCart)) {
            $allCartItems = $cartItemCrud->getAllCartItemFromCartId($currentCart->getCartId());
            $encryptedSize = encryptData(count($allCartItems), $_SESSION[CLIENT_PUBLIC_KEY]);
            $encryptedCartId = encryptData($currentCart->getCartId(), $_SESSION[CLIENT_PUBLIC_KEY]);
        }

    } else {
        header('Location: ../logout.php');
        exit();
    }

?>
<!DOCTYPE html>
<html>

<head>
<title>Shopping Cart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/shoppingcart.css">
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
                        <li><a href="account/">Account</a></li>
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
    <!--
Possible bonus tasks:
1. html number validation - number only, default value, min/max value
2. using post for better security - method="post"
3. real time update - onchange
-->

    <h1 class="aligncenter">Shopping Cart</h1>
    <br>
    <?php if (isset($currentCart)) { ?>
        <input type="hidden" name="cartId" id="cartId" value="" />
        <table>
            <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <?php $i = 0; ?>
            <?php foreach ($allCartItems as $productId => $cartItem):
                    $product = $productCrud->getProductById($productId);
                    $encryptedProductName = encryptData($product->getName(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductId = encryptData($product->getProductId(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductPrice = encryptData($product->getPrice(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductImage = encryptData($product->getImage(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedItemQuantity = encryptData($cartItem->getQuantity(), $_SESSION[CLIENT_PUBLIC_KEY]);
                ?>
                <tr>
                    <td><a id="link<?php echo $i; ?>" href=""><span id="productName<?php echo $i; ?>"></span></a>
                        <input type="hidden" name="Product<?php echo $i; ?>" id="Product<?php echo $i; ?>" value="" />
                    </td>
                    <td><span id="productPrice<?php echo $i; ?>"></span>
                        <input type="hidden" name="Product<?php echo $i; ?>price" id="Product<?php echo $i; ?>price" value="" />
                    </td>
                    <td>
                        <input id="Product<?php echo $i; ?>quantity" name="Product<?php echo $i; ?>quantity" type="number" value="" min="0" max="50"
                            onchange="updateCart('<?php echo $encryptedSize ?>')" />
                        <?php 
                        if ($quantityError) {
                            echo displayError("Please enter a number!"); 
                        }
                        ?>
                    </td>
                    <td>
                        <p id="Product<?php echo $i; ?>subtotal"></p>
                        <input id="Product<?php echo $i; ?>total" name="Product<?php echo $i; ?>total" type="hidden" value="" />
                    </td>
                </tr>
                <script>
                    encryptProductLink("link<?php echo $i; ?>", "<?php echo $productId; ?>", `<?php echo getPublicKey();?>`, '../product/?product=');
                    decryptData('productName<?php echo $i; ?>', '<?php echo $encryptedProductName; ?>', '');
                    setCartInputValue('Product<?php echo $i; ?>', '<?php echo $encryptedProductId; ?>');
                    decryptData('productPrice<?php echo $i; ?>', '<?php echo $encryptedProductPrice; ?>', '$');
                    setCartInputValue('Product<?php echo $i; ?>price', '<?php echo $encryptedProductPrice; ?>');
                    setCartInputValue('Product<?php echo $i; ?>quantity', '<?php echo $encryptedItemQuantity; ?>');
                </script>
                <?php $i++; ?>
            <?php endforeach;?>
            <tr>
                <th>Total</th>
                <th><p id="pPrice"></p></th>
                <th>
                    <p id="ptotalQuantity"></p>
                    <input id="totalQuantity" name="total" type="hidden" value="0" />
                </th>
                <th>
                    <p id="ptotalPrice"></p>
                    <input id="totalPrice" name="totalPrice" type="hidden" value="0" />
                </th>
            </tr>
            
        </table>
        <br>
        <form id="emptyCartForm" method="post">
            <div class="aligncenter">
                <input type="hidden" name="emptyCartInput" id="emptyCartInput" value="" />
                <input id="emptcart" name="emptcart" type="submit" onclick="emptyCart(`<?php echo getPublicKey();?>`)" value="Empty Cart">
            </div>
        </form>
        <br><br><br>
        <div class="formcontainer">
            <h3 class="aligncenter">Place Order Form</h3>
            <br>
            <div class="aftercol">
                <div class="firstcol noselect">
                    <label for="addressInput">Address*:</label>
                </div>
                <div class="secondcol">
                    <input type="text" name="addressInput" id="addressInput" value="" placeholder="542 W. 15th Street" maxlength="50"/>
                </div>
                <?php 
                if ($addressError) {
                    echo displayError("Please an address!"); 
                }
                ?>
            </div>

            <div class="aftercol">
                <div class="firstcol noselect">
                    <label for="creditCardInput">Credit Card*:</label>
                </div>
                <div class="secondcol">
                    <input type="text" name="creditCardInput" id="creditCardInput" value="" placeholder="1111-2222-3333-4444" maxlength="19"/>
                </div>
                <?php 
                if ($creditCardError) {
                    echo displayError("Please enter your credit card number!"); 
                }
                ?>
            </div>

            <div class="aligncenter">
                <br>
                <form id="placeOrderForm" method="post">
                    <input type="hidden" name="placeOrderInput" id="placeOrderInput" value=""/>
                    <input id="placeorder" name="placeorder" type="submit" value="Place Order" onclick="placeOrder(`<?php echo getPublicKey();?>`, '<?php echo $encryptedSize ?>')">
                </form>
            </div>
        </div>
        <br><br>

    <script>
        updateCart('<?php echo $encryptedSize ?>');
        setCartInputValue('cartId', '<?php echo $encryptedCartId; ?>');
    </script>
    <?php } else { ?>
        <div class="aligncenter">You have nothing in your shopping cart</div>
    <?php } ?>
</body>
<script>
    decryptData('usernameProduct', '<?php echo $encryptedUsername; ?>', '');
</script>

</html>