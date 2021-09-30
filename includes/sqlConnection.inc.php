<?php
    class SQLConnection {
        private $pdo;

        public function connect() {
            if ($this->pdo == null) {
                if (file_exists(('../includes/sqlConnection.inc.php'))) {
                    $this->pdo = new PDO("sqlite:../database.db");

                } else {
                    $this->pdo = new PDO("sqlite:database.db");
                }
            }
            return $this->pdo;
        }
    }
?>