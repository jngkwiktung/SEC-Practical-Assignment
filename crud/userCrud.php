<?php
    if (file_exists(('../includes/sqlConnection.inc.php'))) {
        require_once('../includes/sqlConnection.inc.php');
        require_once('../model/user.php');
    } else {
        require_once('includes/sqlConnection.inc.php');
        require_once('model/user.php');
    }

    class UserCrud {
        
        public function create(User $user) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("INSERT INTO USER (USER_ID, USERNAME, PASSWORD) VALUES (?, ?, ?)");
                $stmt->execute([$user->getUserId(), $user->getUsername(), $user->getPassword()]);

            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function readAll() {
            try {
                $users = [];
                $pdo = (new SQLConnection())->connect();
                $sql = "SELECT * FROM USER";
                $result = $pdo->query($sql);
                foreach ($result as $row) {
                    // echo "USER_ID: " . $row["USER_ID"]. " - USERNAME: " . $row["USERNAME"]. " " . $row["PASSWORD"]. "<br>";
                    array_push($users, new User($row["USER_ID"], $row["USERNAME"], $row["PASSWORD"]));
                }
                return $users;
            } catch (Exception $e) {
                error_log($e->getMessage());
                return [];
            }
        }

        public function update(User $user) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("UPDATE USER SET USERNAME = ? AND PASSWORD = ? WHERE USER_ID = ?");
                $stmt->execute([$user->getUsername(), $user->getPassword(), $user->getUserId()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function delete(User $user) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("DELETE FROM USER WHERE USER_ID = ?");
                $stmt->execute([$user->getUserId()]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        public function isLogin($username, $password) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("SELECT * FROM USER WHERE USERNAME = ? AND PASSWORD = ?");
                $stmt->execute([$username, $password]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    return new User($row["USER_ID"], $row["USERNAME"], $row["PASSWORD"]);
                }
                return null;

            } catch (Exception $e) {
                error_log($e->getMessage());
                return null;
            }
        }

        public function usernameExists($username) {
            try {
                $pdo = (new SQLConnection())->connect();
                $stmt = $pdo->prepare("SELECT * FROM USER WHERE USERNAME = ?");
                $stmt->execute([$username]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    return true;
                }
                return false;

            } catch (Exception $e) {
                error_log($e->getMessage());
                return false;
            }
        }
    }
?>