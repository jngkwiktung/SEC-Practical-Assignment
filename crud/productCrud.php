<?php
    require_once('../includes/sqlConnection.inc.php');
    require_once('../model/product.php');

    class ProductCrud {
        public function create(Product $product) {
            try {
                $pdo = (new SQLConnection())->connect();
                //TODO
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function delete(Product $product) {
            try {
                $pdo = (new SQLConnection())->connect();
                //TODO
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
?>