<?php

class ProductController
{
    private $method;
    public $id;

    public function __construct(private $gateway)
    {
        // Separate url components
        $req = explode("/", $_SERVER['REQUEST_URI']);

        // Get method and ID
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->id = $req[2] ?? null;
    }

    public function handleRequest()
    {
        switch ($this->method) {
            case "GET":
                echo json_encode($this->gateway->getProducts());
                break;
            case "POST":
                echo json_encode($this->gateway->insertProduct());
                break;
            case "DELETE":
                echo json_encode($this->gateway->deleteProduct($this->id));
                break;
            default:
                echo "404 Not Found a3chiri";
                break;
        };
    }
}
