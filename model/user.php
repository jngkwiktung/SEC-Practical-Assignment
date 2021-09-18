<?php

class User {
    private int $userId;
    private String $username;
    private String $password;

    public function __construct(int $userId, String $username, String $password) {
        $this->userId = $userId;
        $this->username = $username;
        $this->password = $password;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername(String $username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword(String $password) {
        $this->password = $password;
    }
}

?>