<?php
require_once('../includes/sqlConnection.inc.php');
require_once('../model/cartItem.php');

class CartItemCrud
{
    public function create(CartItem $cartItem)
    {
        try {
            $pdo = (new SQLConnection())->connect();
            //TODO
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function delete(CartItem $cartItem)
    {
        try {
            $pdo = (new SQLConnection())->connect();
            //TODO
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getAllCartItemFromCartId($cartId)
    {
        try {
            $items = [];
            $pdo = (new SQLConnection())->connect();
            $sql = "SELECT * FROM CARTITEM WHERE CART_ID = " . $cartId;
            $result = $pdo->query($sql);
            foreach ($result as $row) {
                // echo "USER_ID: " . $row["USER_ID"]. " - USERNAME: " . $row["USERNAME"]. " " . $row["PASSWORD"]. "<br>";
                $items[$row["PRODUCT_ID"]] = new CartItem($row["PRODUCT_ID"], $row["CART_ID"], $row["QUANTITY"]);
            }
            return $items;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
