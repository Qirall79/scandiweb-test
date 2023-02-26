<?php
// Auto loader
spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

// Error handler
set_exception_handler("ErrorHandler::handleError");

// Db connection
$db = new Database("localhost", "products_db", "root", "");

// Gateway
$gateway = new ProductGateway($db);

// Handle request
$controller = new ProductController($gateway);
$controller->handleRequest();
