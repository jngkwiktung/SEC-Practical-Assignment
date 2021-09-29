<?php
require_once('../includes/sqlConnection.inc.php');
require_once('../model/product.php');

class ProductCrud
{
    public function create(Product $product)
    {
        try {
            $pdo = (new SQLConnection())->connect();
            //TODO
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function delete(Product $product)
    {
        try {
            $pdo = (new SQLConnection())->connect();
            //TODO
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function readOne($productId)
    {
        try {
            // TODO
            $pdo = (new SQLConnection())->connect();
            $sql = "SELECT * FROM [PRODUCT] WHERE PRODUCT_ID = " . $productId;
            $result = $pdo->query($sql);
            foreach ($result as $row) {
                return new Product($row["PRODUCT_ID"], $row["NAME"], $row["PRICE"], $row["DESCRIPTION"], $row["MANUFACTURER"], $row["IMAGE"], $row["CATEGORY"]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function readCartItems($cartItems)
    {
        try {
            $products = [];
            $arrayOfProductIds = [];
            foreach ($cartItems as $item) {
                array_push($arrayOfProductIds, $item->getProductId());
            }
            $arrayString = implode(",", $arrayOfProductIds);
            $pdo = (new SQLConnection())->connect();
            $sql = "SELECT * FROM [PRODUCT] WHERE PRODUCT_ID IN (" . $arrayString . ")";
            $result = $pdo->query($sql);
            foreach ($result as $row) {
                array_push($products, new Product($row["PRODUCT_ID"], $row["NAME"], $row["PRICE"], $row["DESCRIPTION"], $row["MANUFACTURER"], $row["IMAGE"], $row["CATEGORY"]));
            }
            return $products;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
