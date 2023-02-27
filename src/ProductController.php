<?php

class ProductController
{
    private $method;
    public $gateway;
    public $req;

    public function __construct($gateway)
    {
        $this->gateway = $gateway;

        // Separate url components
        $this->req = explode("/", $_SERVER['REQUEST_URI']);

        // Get method and ID
        $this->method = $_SERVER["REQUEST_METHOD"];
    }

    public function handleRequest()
    {

        switch ($this->method) {
            case "GET":
                echo json_encode($this->gateway->getProducts());
                break;
            case "POST":
                if (count($this->req) >= 2 && $this->req[2] === "") {
                    echo json_encode($this->gateway->insertProduct());
                    break;
                }
                if (count($this->req) >= 3 && $this->req[2] === "delete") {
                    $json = file_get_contents('php://input');
                    $data = json_decode($json, true);

                    $sku = (int) $data["sku"];
                    echo json_encode($this->gateway->deleteProduct($sku));
                    break;
                } else {
                    http_response_code(404);
                    echo json_encode(["error" => "Page not found"]);
                }
                break;
            default:
                http_response_code(403);
                echo json_encode(["error" => "Unauthorized"]);
                break;
        };
    }
}
