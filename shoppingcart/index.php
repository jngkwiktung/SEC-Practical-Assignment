<?php require_once('../crud/shoppingCartCrud.php'); ?>
<?php require_once('../crud/cartItemCrud.php'); ?>
<?php require_once('../crud/productCrud.php'); ?>
<?php require_once('../includes/authorise.inc.php'); ?>
<?php require_once('../model/shoppingCart.php'); ?>
<?php require_once('../model/user.php'); ?>
<?php require_once('../model/cartItem.php'); ?>
<?php require_once('../model/product.php'); ?>
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
    <!--
Possible bonus tasks:
1. html number validation - number only, default value, min/max value
2. using post for better security - method="post"
3. real time update - onchange
-->

    <h1 class="aligncenter">Shopping Cart</h1>
    <br>
        <table>
            <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <tr>
                <td>Product A
                    <input type="hidden" name="ProductA" id="ProductA" value="ProductA" />
                </td>
                <td>$10
                    <input type="hidden" name="ProductAprice" id="ProductAprice" value="10" />
                </td>
                <td>
                    <input id="ProductAquantity" name="ProductAquantity" type="number" value="0" min="0" max="10"
                        onchange="updateCart()" />
                </td>
                <td>
                    <p id="ProductAsubtotal">0</p>
                    <input id="ProductAtotal" name="ProductAtotal" type="hidden" value="0" />
                </td>
            </tr>
            <tr>
                <td>Product B
                    <input type="hidden" name="ProductB" id="ProductB" value="ProductB" />
                </td>
                <td>$15
                    <input type="hidden" name="ProductBprice" id="ProductBprice" value="15" />
                </td>
                <td>
                    <input id="ProductBquantity" name="ProductBquantity" type="number" value="0" min="0" max="10"
                        onchange="updateCart()" />
                </td>
                <td>
                    <p id="ProductBsubtotal">0</p>
                    <input id="ProductBtotal" name="ProductBtotal" type="hidden" value="0" />
                </td>
            </tr>
            <tr>
                <td>Product C
                    <input type="hidden" name="ProductC" id="ProductC" value="ProductC" />
                </td>
                <td>$20
                    <input type="hidden" name="ProductCprice" id="ProductCprice" value="20" />
                </td>
                <td>
                    <input id="ProductCquantity" name="ProductCquantity" type="number" value="0" min="0" max="10"
                        onchange="updateCart()" />
                </td>
                <td>
                    <p id="ProductCsubtotal">0</p>
                    <input id="ProductCtotal" name="ProductCtotal" type="hidden" value="0" />
                </td>
            </tr>
            <tr>
                <th>Total</th>
                <th></th>
                <th>
                    <p id="Quantity">0</p>
                    <input id="totalQuantity" name="total" type="hidden" value="0" />
                </th>
                <th>
                    <p id="Price">0</p>
                    <input id="totalPrice" name="totalPrice" type="hidden" value="0" />
                </th>
            </tr>
            
        </table>
        <br>
        <div class="aligncenter">
            <button id="emptcart" type="empty" onclick="window.location.reload();">Empty Cart</button>
            <button id="placeorder" type="submit">Place Order</button>
        </div>

    <script src="cartfinal.js"></script>
    
</body>

</html>