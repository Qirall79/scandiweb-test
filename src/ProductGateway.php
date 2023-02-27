<?php

class ProductGateway
{
    public $conn;

    public function __construct($db)
    {
        $this->conn = $db->getConnection();
    }

    public function getProducts()
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $products;
    }

    public function insertProduct()
    {
        $data = $_POST;
        $input = [
            "sku" => array_key_exists("sku", $data) ? (int) $data["sku"] : 0,
            "name" => $data["name"] ?? null,
            "price" => array_key_exists("price", $data) ? (int) $data["price"] : 0,
            "type" => $data["type"] ?? null,
        ];

        $product = [];

        // get product based on type using inheritance
        switch ($input["type"]) {
            case "book":
                $product = new Book($data, $this->conn);
                break;
            case "dvd":
                $product = new Dvd($data, $this->conn);
                break;
            case "furniture":
                $product = new Furniture($data, $this->conn);
                break;
            default:
                echo "Invalid product type ";
                return $input["type"];
        }

        // Get validation errors
        $errors = [];
        $inputErrors = $product->validateInput();
        $attributesErrors = $product->validateAttributes();

        if (count($inputErrors)) array_push($errors, ...$inputErrors);
        if (count($attributesErrors)) array_push($errors, ...$attributesErrors);



        // Display errors if any
        if (!empty($errors)) {
            http_response_code(422);
            return ["errors" => $errors];
        }

        // Insert product into database
        $sql = "INSERT INTO 
        products(sku, name, price, type, weight, size, width, length, height) 
        VALUES(:sku, :name, :price, :type, :weight, :size, :width, :length, :height)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "sku" => array_key_exists("sku", $data) ? (int) $data["sku"] : 0,
            "name" => $data["name"] ?? null,
            "price" => array_key_exists("price", $data) ? (int) $data["price"] : 0,
            "type" => $data["type"] ?? null,
            "weight" => array_key_exists("weight", $data) ? (float) $data["weight"] : null,
            "height" => array_key_exists("height", $data) ? (float) $data["height"] : null,
            "width" => array_key_exists("width", $data) ? (float) $data["width"] : null,
            "length" => array_key_exists("length", $data) ? (float) $data["length"] : null,
            "size" => array_key_exists("size", $data) ? (float) $data["size"] : null,
        ]);

        return $product->input;
    }

    // Delete item by sku key
    public function deleteProduct($sku)
    {
        if (!is_numeric($sku)) {
            http_response_code(501);
            return ["message" => "Invalid sku key"];
        }
        if ($sku <= 0) {
            http_response_code(500);
            return ["message" => "Invalid sku key"];
        }
        $sql = "DELETE FROM products WHERE sku = :sku";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["sku" => $sku]);
        return ["message" => "Product with sku $sku is deleted."];
    }
}
