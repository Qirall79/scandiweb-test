<?php

class Product
{

    public $input;
    public $conn;
    public $productAttributes;

    public function __construct($input, $conn)
    {
        $this->conn = $conn;
        $this->input = $input;
        $this->productAttributes = [
            "book" => ["weight"],
            "dvd" => ["size"],
            "furniture" => ["height", "width", "length"]
        ];
    }

    public function validateInput()
    {
        $errors = [];
        if (!$this->validateSku($this->input["sku"])) {
            $errors[] = "SKU already exists";
        }
        if (strlen($this->input["sku"]) == 0) {
            $errors[] = "Invalid SKU";
        }
        if (!$this->input["name"]) {
            $errors[] = "Name field is required";
        }
        if (!(is_numeric($this->input["price"]) && (float) $this->input["price"] > 0)) {
            $errors[] = "Invalid price field";
        }
        if (!$this->input["type"] || !in_array($this->input["type"], ["book", "dvd", "furniture"])) {
            $errors[] = "Type field is invalid";
        }
        return $errors;
    }

    public function validateAttributes()
    {
        $errors = [];

        if (!array_key_exists($this->input["type"], $this->productAttributes)) {
            return [];
        }

        // This will extract necessary attributes based on type
        $attributes = $this->productAttributes[$this->input["type"]];

        // check if all necessary attributes are valid
        foreach ($attributes as $attr) {
            if (!(is_numeric($this->input[$attr]) && (float) $this->input[$attr] >= 0)) {
                $errors[] = "Invalid attribute " . $attr;
            }
        }
        return $errors;
    }

    public function validateSku($sku)
    {

        // Check if SKU already exists in the database
        $sql = "SELECT * FROM products WHERE sku = :sku";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["sku" => strtolower($sku)]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) return false;
        return true;
    }
}
