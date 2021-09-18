<?php
    require_once('../includes/sqlConnection.inc.php');
    require_once('../model/cartItem.php');

    class CartItemCrud {
        public function create(CartItem $cartItem) {
            try {
                $pdo = (new SQLConnection())->connect();
                //TODO
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function delete(CartItem $cartItem) {
            try {
                $pdo = (new SQLConnection())->connect();
                //TODO
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
?>