<?php
    require_once('../includes/sqlConnection.inc.php');
    require_once('../model/shoppingCart.php');

    class ShoppingCartCrud {
        public function create(ShoppingCart $shoppingCart) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("INSERT INTO SHOPPINGCART (CART_ID, USER_ID) VALUES (?, ?)");
                $stmt->execute([$shoppingCart->getCartId(), $shoppingCart->getUserId()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function delete(ShoppingCart $shoppingCart) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("DELETE FROM SHOPPINGCART WHERE CART_ID = ?");
                $stmt->execute([$shoppingCart->getCartId()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function getCartById($cartId) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("SELECT * FROM SHOPPINGCART WHERE CART_ID = ?");
                $stmt->execute([$cartId]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    return new ShoppingCart($row["CART_ID"], $row["USER_ID"]);
                }
                return null;

            } catch (Exception $e) {
                error_log($e->getMessage());
                return null;
            }
        }

        public function getCartsByUserId($userId) {
            try {
                $carts = [];
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("SELECT * FROM SHOPPINGCART WHERE USER_ID = ?");
                $stmt->execute([$userId]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($carts, new ShoppingCart($row["CART_ID"], $row["USER_ID"]));
                }
                return $carts;

            } catch (Exception $e) {
                error_log($e->getMessage());
                return [];
            }
        }
    }
?>