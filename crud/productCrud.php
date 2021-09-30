<?php
    if (file_exists(('../includes/sqlConnection.inc.php'))) {
        require_once('../includes/sqlConnection.inc.php');
        require_once('../model/product.php');
    } else {
        require_once('includes/sqlConnection.inc.php');
        require_once('model/product.php');
    }

    class ProductCrud {
        public function create(Product $product) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("INSERT INTO PRODUCT (PRODUCT_ID, NAME, PRICE, DESCRIPTION, MANUFACTURER, IMAGE, CATEGORY) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$product->getProductId(), $product->getName(), $product->getPrice(), $product->getDescription(), $product->getManufacturer(), $product->getImage(), $product->getImage()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function readAll() {
            try {
                $product = [];
                $pdo = (new SQLConnection())->connect();
                $sql = "SELECT * FROM PRODUCT";
                $result = $pdo->query($sql);
                foreach ($result as $row) {
                    array_push($product, new Product($row["PRODUCT_ID"], $row["NAME"], $row["PRICE"], $row["DESCRIPTION"], $row["MANUFACTURER"], $row["IMAGE"], $row["CATEGORY"]));
                }
                return $product;
            } catch (Exception $e) {
                error_log($e->getMessage());
                return [];
            }
        }

        public function delete(Product $product) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("DELETE FROM PRODUCT WHERE PRODUCT_ID = ?");
                $stmt->execute([$product->getProductId()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function getProductById($productId) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("SELECT * FROM PRODUCT WHERE PRODUCT_ID = ?");
                $stmt->execute([$productId]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    return new Product ($row["PRODUCT_ID"], $row["NAME"], $row["PRICE"], $row["DESCRIPTION"], $row["MANUFACTURER"], $row["IMAGE"], $row["CATEGORY"]);
                }
                return null;

            } catch (Exception $e) {
                error_log($e->getMessage());
                return null;
            }
        }
    }
?>