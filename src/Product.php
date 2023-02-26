<?php

class Product
{

    public $input;

    public function __construct($input, private $conn)
    {
        $this->input = $input;
    }

    public function validateInput()
    {
        $errors = [];
        if (!$this->validateSku($this->input["sku"])) {
            $errors[] = "SKU already exists";
        }
        if ($this->input["sku"] == 0) {
            $errors[] = "Invalid SKU";
        }
        if (!$this->input["name"]) {
            $errors[] = "Name field is required";
        }
        if (!$this->input["price"]) {
            $errors[] = "Price field is required";
        }
        if (!$this->input["type"]) {
            $errors[] = "Type field is required";
        }
        return $errors;
    }

    public function validateSku($sku)
    {

        // Check if SKU already exists in the database
        $sql = "SELECT * FROM products WHERE sku = :sku";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["sku" => $sku]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) return false;
        return true;
    }
}
