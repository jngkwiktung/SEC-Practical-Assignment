<?php require_once('../crud/orderCrud.php'); ?>
<?php require_once('../crud/cartItemCrud.php'); ?>
<?php require_once('../crud/productCrud.php'); ?>
<?php require_once('../crud/userCrud.php'); ?>
<?php require_once('../includes/authorise.inc.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php require_once('../model/order.php'); ?>
<?php
if (isset($_GET["order_id"])) {
    // TODO DATE CONVERSION FOR ORDER
    openssl_private_decrypt(base64_decode(str_replace(' ', '+', $_GET["order_id"])), $orderId, $_SESSION[PRIVATE_KEY]);
    $orderCrud = new OrderCrud();
    $cartItemCrud = new CartItemCrud();
    $productCrud = new ProductCrud();
    $order = $orderCrud->readOne($orderId);
    $cartItems = $cartItemCrud->getAllCartItemFromCartId($order->getCartId());
    $products = $productCrud->readCartItems($cartItems);

    // EXAMPLE PRODUCT DIV
    /*
                    <div class="product">
                    <div class="product__image-div">
                        <img class="product__image" src="../images/horizon_forbidden_west_ps5.PNG">
                    </div>
                    <div class="product__card">
                        <div class="product__row">
                            <div class="product__name">
                                Horizon Forbidden West PS5 Game
                            </div>
                            <div class="product__price">
                                $69.95
                            </div>
                        </div>
                        <div class="product__quantity">
                            Quantity: 1
                        </div>
                        <div class="product__manufacturer">
                            Playstation Studios
                        </div>
                        <div class="product__category">
                            PS5
                        </div>
                    </div>
                </div>
    */

    $productDivArray = [];
    foreach ($products as $product) {
        array_push($productDivArray, '
        <div class="product">
            <div class="product__image-div">
                <img class="product__image" src="../images/' . $product->getImage() . '">
            </div>
            <div class="product__card">
            <div class="product__row">
                <div class="product__name">
                    ' . $product->getName() . '
                </div>
                <div class="product__price">
                    $' . number_format($product->getPrice(), 2) .  '
                </div>
            </div>
            <div class="product__quantity">
                Quantity: ' . $cartItems[$product->getProductId()]->getQuantity() . '
            </div>
            <div class="product__manufacturer">
                ' . $product->getManufacturer() . '
            </div>
            <div class="product__category">
                ' . $product->getCategory() . '
            </div>
        </div>
        </div>
        ');
    }
} else {
    header('location:/SEC-Practical-Assignment/');
}
?>
<!doctype html>
<html>

<head>
    <title>Order Summary</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/app_icon.png">
    <link rel="stylesheet" type="text/css" href="../styles/order.css">
</head>

<body class="content">
    <header>
        <!-- Put website header here -->
    </header>
    <h1>Thank you! Your order has been received.</h1>
    <article>
        <div class="order">
            <h2>Order #<?php echo $order->getOrderId() ?> - Steven Harjapramana</h2>
            <div class="order-summary-div">
                <h1 class="text-uppercase">Order Confirmation</h1>
                <div>
                    <img src="../images/order-confirmation.svg" class="order-confirmation-img">
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
                        <td>#<?php echo $order->getOrderId() ?></td>
                        <td>September 20, 2021</td>
                        <td>$<?php echo number_format($order->getTotal(), 2) ?></td>
                        <td>Credit Card</td>
                    </tbody>
                </table>
            </div>
            <div class="product-list">
                <!-- <div class="product">
                    <div class="product__image-div">
                        <img class="product__image" src="../images/horizon_forbidden_west_ps5.PNG">
                    </div>
                    <div class="product__card">
                        <div class="product__row">
                            <div class="product__name">
                                Horizon Forbidden West PS5 Game
                            </div>
                            <div class="product__price">
                                $69.95
                            </div>
                        </div>
                        <div class="product__quantity">
                            Quantity: 1
                        </div>
                        <div class="product__manufacturer">
                            Playstation Studios
                        </div>
                        <div class="product__category">
                            PS5
                        </div>
                    </div>
                </div>
                <div class="product">
                    <div class="product__image-div">
                        <img class="product__image" src="../images/horizon_forbidden_west_ps5.PNG">
                    </div>
                    <div class="product__card">
                        <div class="product__row">
                            <div class="product__name">
                                Horizon Forbidden West PS5 Game
                            </div>
                            <div class="product__price">
                                $69.95
                            </div>
                        </div>
                        <div class="product__quantity">
                            Quantity: 1
                        </div>
                        <div class="product__manufacturer">
                            Manufacturer: Playstation Studios
                        </div>
                        <div class="product__category">
                            Category: PS5
                        </div>
                    </div>
                </div> -->
                <?php foreach ($productDivArray as $productToDisplay) {
                    echo $productToDisplay;
                } ?>
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
                        <div class="total__value">$<?php echo number_format($order->getTotal(), 2) ?></div>
                    </div>
                    <form id="logoutForm" action="/SEC-Practical-Assignment/logout.php" method="post" class="cta-form flex-center flex-align-center">
                        <!-- <input name="logout" id="logout" type="submit" value="Logout" class="btn"> -->
                        <button class="btn">Order Again</button>
                    </form>
                </div>
            </div>
        </div>

    </article>
    <footer>
        <p>&copy; 2021 Jeremy Ng Kwik Tung, Steven Harja, Wilbert Ongosari & Aman Khan</p>
    </footer>
</body>

</html>