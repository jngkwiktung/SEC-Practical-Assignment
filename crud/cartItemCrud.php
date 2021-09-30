<?php
    require_once('../includes/sqlConnection.inc.php');
    require_once('../model/cartItem.php');

    class CartItemCrud {
        public function create(CartItem $cartItem) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("INSERT INTO CARTITEM (PRODUCT_ID, CART_ID, QUANTITY) VALUES (?, ?, ?)");
                $stmt->execute([$cartItem->getProductId(), $cartItem->getCartId(), $cartItem->getQuantity()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function update(CartItem $cartItem) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("UPDATE CARTITEM SET QUANTITY = ? WHERE PRODUCT_ID = ? AND CART_ID = ?");
                $stmt->execute([$cartItem->getQuantity(), $cartItem->getProductId(), $cartItem->getCartId()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function delete(CartItem $cartItem) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("DELETE FROM CARTITEM WHERE PRODUCT_ID = ? AND CART_ID = ?");
                $stmt->execute([$cartItem->getProductId(), $cartItem->getCartId()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function getOneCartItem($productId, $cartId) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("SELECT * FROM CARTITEM WHERE PRODUCT_ID = ? AND CART_ID = ?");
                $stmt->execute([$productId, $cartId]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    return new CartItem($row["PRODUCT_ID"], $row["CART_ID"], $row["QUANTITY"]);
                }
                return null;

            } catch (Exception $e) {
                error_log($e->getMessage());
                return null;
            }
        }
    }
?>