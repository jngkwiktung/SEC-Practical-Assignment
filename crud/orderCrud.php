<?php
require_once('../includes/sqlConnection.inc.php');
require_once('../model/order.php');

class OrderCrud
{
    public function create(Order $order)
    {
        try {
            $pdo = (new SQLConnection())->connect();
            $stmt = $pdo->prepare("INSERT INTO ORDER (ORDER_ID, CART_ID, TOTAL, ADDRESS, CREDIT_CARD, TIMESTAMP) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$order->getOrderId(), $order->getCartId(), $order->getTotal(), $order->getAddress(), $order->getCreditCard(), $order->getTimestamp()]);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function readOne(Order $order)
    {
        try {
            // TODO
            $pdo = (new SQLConnection())->connect();
            $stmt = $pdo->prepare("SELECT * FROM ORDER WHERE ORDER_ID = ? AND CART_ID = ?");
            $stmt->execute([$order->getOrderId(), $order->getCartId()]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function readAll()
    {
        try {
            $orders = [];
            $pdo = (new SQLConnection())->connect();
            $sql = "SELECT * FROM ORDER";
            $result = $pdo->query($sql);
            foreach ($result as $row) {
                array_push($orders, new Order($row["ORDER_ID"], $row["CART_ID"], $row["TOTAL"], $row["ADDRESS"], $row["CREDIT_CARD"], $row["TIMESTAMP"]));
            }
            return $orders;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function update(Order $order)
    {
        try {
            $pdo = (new SQLConnection())->connect();
            $stmt = $pdo->prepare("UPDATE ORDER SET TOTAL = ? AND ADDRESS = ? AND CREDIT_CARD = ? AND TIMESTAMP = ? WHERE ORDER_ID = ? AND CART_ID = ?");
            $stmt->execute([$order->getTotal(), $order->getAddress(), $order->getCreditCard(), $order->getTimestamp(), $order->getOrderId(), $order->getCartId()]);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function delete(Order $order)
    {
        try {
            $pdo = (new SQLConnection())->connect();
            $stmt = $pdo->prepare("DELETE FROM ORDER WHERE ORDER_ID = ? AND CART_ID = ?");
            $stmt->execute([$order->getOrderId(), $order->getCartId()]);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
