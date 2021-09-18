<?php
    class SQLConnection {
        private $pdo;

        public function connect() {
            if ($this->pdo == null) {
                $this->pdo = new PDO("sqlite:../database.db");
            }
            return $this->pdo;
        }
    }
?>