<?php

class ShoppingCart {
    private int $cartId;
    private int $userId;

    public function __construct(int $cartId, int $userId) {
        $this->cartId = $cartId;
        $this->userId = $userId;
    }

    public function getCartId() {
        return $this->cartId;
    }

    public function getUserId() {
        return $this->userId;
    }

}

?>