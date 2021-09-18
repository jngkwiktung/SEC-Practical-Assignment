<?php

class Order {
    private int $orderId;
    private int $cartId;
    private Float $total;
    private String $address;
    private String $creditCard;
    private int $timestamp;

    public function __construct(int $orderId, int $cartId, Float $total, String $address, String $creditCard, int $timestamp) {
        $this->orderId = $orderId;
        $this->cartId = $cartId;
        $this->total = $total;
        $this->address = $address;
        $this->creditCard = $creditCard;
        $this->timestamp = $timestamp;
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getCartId() {
        return $this->cartId;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCreditCard() {
        return $this->creditCard;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

}

?>