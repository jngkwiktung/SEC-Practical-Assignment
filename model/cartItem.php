<?php

class CartItem {
    private int $productId;
    private int $cartId;
    private String $quantity;

    public function __construct(int $productId, int $cartId, String $quantity) {
        $this->productId = $productId;
        $this->cartId = $cartId;
        $this->quantity = $quantity;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getCartId() {
        return $this->cartId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity(String $quantity) {
        $this->quantity = $quantity;
    }
}

?>