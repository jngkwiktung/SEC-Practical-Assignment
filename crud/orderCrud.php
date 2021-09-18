<?php
    require_once('../includes/sqlConnection.inc.php');
    require_once('../model/order.php');

    class OrderCrud {
        public function create(Order $order) {
            try {
                $pdo = (new SQLConnection())->connect();
                //TODO
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function delete(Order $order) {
            try {
                $pdo = (new SQLConnection())->connect();
                //TODO
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
?>