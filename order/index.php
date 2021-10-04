<?php require_once('../crud/orderCrud.php'); ?>
<?php require_once('../crud/cartItemCrud.php'); ?>
<?php require_once('../crud/productCrud.php'); ?>
<?php require_once('../crud/userCrud.php'); ?>
<?php require_once('../includes/authorise.inc.php'); ?>
<?php require_once('../includes/functions.inc.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php require_once('../model/order.php'); ?>
<?php
if (isset($_GET["order_id"]) && isset($_SESSION[DES_SESSION_KEY])) {
    // TODO DATE CONVERSION FOR ORDER
    $currentUser = getLoggedInUser();
    $encryptedUsername = encryptData($currentUser->getUsername(), $_SESSION[CLIENT_PUBLIC_KEY]);
    $encryptedSessionKey = encryptData($_SESSION[DES_SESSION_KEY], $_SESSION[CLIENT_PUBLIC_KEY]);
    openssl_private_decrypt(base64_decode(str_replace(' ', '+', $_GET["order_id"])), $orderId, $_SESSION[PRIVATE_KEY]);
    $orderCrud = new OrderCrud();
    $cartItemCrud = new CartItemCrud();
    $productCrud = new ProductCrud();
    $order = $orderCrud->readOne($orderId);
    $encryptedOrderId = encryptData($order->getOrderId(), $_SESSION[CLIENT_PUBLIC_KEY]);
    $encryptedOrderTotal = encryptData($order->getTotal(), $_SESSION[CLIENT_PUBLIC_KEY]);
    $cartItems = $cartItemCrud->getAllCartItemFromCartId($order->getCartId());
    $products = $productCrud->readCartItems($cartItems);
} else {
    header('location:/SEC-Practical-Assignment/');
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Summary</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/order.css">
    <link rel="stylesheet" type="text/css" href="../styles/product.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="../scripts/sha256.js"></script>
    <script type="text/javascript" src="../scripts/rsa.js"></script>
    <script type="text/javascript" src="../scripts/functions.js"></script>
</head>

<body class="content" onload="checkSessionKey(`<?php echo $encryptedSessionKey; ?>`)">
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
                        <li style="color: #000000">Welcome '<span id="usernameOrder"></span>'!</li>
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
    <br>
    <h1>Thank you! Your order has been received.</h1>
    <br><br>
    <article>
        <div class="order">
            <h2>Order #<span id="order-id-top"></span></h2>
            <br>
            <div class="order-summary-div">
                <h1 class="text-uppercase">Order Confirmation - <span id="usernameOrderSummary"></span></h1>
                <br>
                <div>
                    <img src="../images/order-confirmation.svg" class="order-confirmation-img">
                    <br><br>
                    <p>
                        Thank you for your order! The product(s) will be delivered to you once we have fully processed the payment.
                    </p>
                </div>
            </div>
            <div class="flex-center">
                <table class="order-summary">
                    <thead>
                        <th>Order Number</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                    </thead>
                    <tbody>
                        <td>#<span id="order-id-bottom"></span></td>
                        <td><?php echo date('d M Y', $order->getTimestamp()) ?></td>
                        <td>$<span id="order-total-top"></span></td>
                        <td>Credit Card</td>
                    </tbody>
                </table>
            </div>
            <div class="product-list">
                <?php $i = 0; ?>
                <?php foreach ($products as $product) {
                    $encryptedProductName = encryptData($product->getName(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductId = encryptData($product->getProductId(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductPrice = encryptData($product->getPrice(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductImage = encryptData($product->getImage(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedItemQuantity = encryptData($cartItems[$product->getProductId()]->getQuantity(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductManufacturer = encryptData($product->getManufacturer(), $_SESSION[CLIENT_PUBLIC_KEY]);
                    $encryptedProductCategory = encryptData($product->getCategory(), $_SESSION[CLIENT_PUBLIC_KEY]);
                ?>
                    <div class="product">
                        <div class="product__image-div">
                            <img class="product__image" id="product__image-<?php echo $i; ?>" src="" />
                        </div>
                        <div class="product__card">
                            <div class="product__row">
                                <div class="product__name" id="product__name-<?php echo $i; ?>">
                                </div>
                                <div class="product__price" id="product__price-<?php echo $i; ?>">
                                </div>
                            </div>
                            <div class="product__quantity">
                                Quantity: <span id="product__quantity-<?php echo $i; ?>"></span>
                            </div>
                            <div class="product__manufacturer" id="product__manufacturer-<?php echo $i; ?>">
                            </div>
                            <div class="product__category" id="product__category-<?php echo $i; ?>">
                            </div>
                        </div>
                    </div>
                    <script>
                        decryptImage('product__image-<?php echo $i; ?>', '<?php echo $encryptedProductImage; ?>', '../images/');
                        decryptData('product__name-<?php echo $i; ?>', '<?php echo $encryptedProductName; ?>', '');
                        decryptData('product__price-<?php echo $i; ?>', '<?php echo $encryptedProductPrice; ?>', '$');
                        decryptData('product__quantity-<?php echo $i; ?>', '<?php echo $encryptedItemQuantity; ?>', '');
                        decryptData('product__manufacturer-<?php echo $i; ?>', '<?php echo $encryptedProductManufacturer; ?>', '');
                        decryptData('product__category-<?php echo $i; ?>', '<?php echo $encryptedProductCategory; ?>', '');
                    </script>
                    <?php $i++; ?>
                <?php } ?>
            </div>
            <div class="flex-center customer">
                <div class="customer__address">

                </div>
                <div class="customer__creditcard">

                </div>
            </div>
            <div class="flex-end flex-align-center">
                <div class="total">
                    <div class="total__item flex-between flex-align-center">
                        <div class="total__label">Total</div>
                        <div class="total__value">$<span id="order-total-bottom"></span></div>
                    </div>
                    <a href="../"><button class="btn">Go back to catalog</button></a>
                </div>
            </div>
        </div>
    </article>
    <br>
    <footer>
        <p>&copy; 2021 Jeremy Ng Kwik Tung, Steven Harja, Wilbert Ongosari & Aman Khan</p>
    </footer>
    <br>
</body>
<script>
    decryptData('usernameOrder', '<?php echo $encryptedUsername; ?>', '');
    decryptData('usernameOrderSummary', '<?php echo $encryptedUsername; ?>', '');
    decryptData('order-id-top', '<?php echo $encryptedOrderId; ?>', '');
    decryptData('order-id-bottom', '<?php echo $encryptedOrderId; ?>', '');
    decryptData('order-total-top', '<?php echo $encryptedOrderTotal ?>', '');
    decryptData('order-total-bottom', '<?php echo $encryptedOrderTotal ?>', '');
</script>

</html>