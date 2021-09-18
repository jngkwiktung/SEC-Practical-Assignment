<?php
    require_once('../includes/sqlConnection.inc.php');
    require_once('../model/shoppingCart.php');

    class ShoppingCartCrud {
        public function create(ShoppingCart $shoppingCart) {
            try {
                $pdo = (new SQLConnection())->connect();
                //TODO
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function delete(ShoppingCart $shoppingCart) {
            try {
                $pdo = (new SQLConnection())->connect();
                //TODO
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
?>