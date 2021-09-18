<?php

class Product {
    private int $productId;
    private String $name;
    private Float $price;
    private String $description;
    private String $manufacturer;
    private String $image;
    private String $category;

    public function __construct(int $productId, String $name, Float $price, String $description, String $manufacturer, String $image, String $category) {
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->manufacturer = $manufacturer;
        $this->image = $image;
        $this->category = $category;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getName() {
        return $this->name;
    }

    public function setName(String $name) {
        $this->name = $name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice(Float $price) {
        $this->price = $price;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription(String $description) {
        $this->description = $description;
    }

    public function getManufacturer() {
        return $this->manufacturer;
    }

    public function setManufacturer(String $manufacturer) {
        $this->manufacturer = $manufacturer;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage(String $image) {
        $this->image = $image;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory(String $category) {
        $this->category = $category;
    }
}

?>